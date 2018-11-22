<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


namespace Evento\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Application\Entity\Post;

class EventoVentaController extends EventoController
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;
    
     /**
     * Evento manager.
     * @var User\Service\EventoManager 
     */
    
    protected $eventoManager;

    
    public function __construct($entityManager, $eventoManager)
    {
        $this->entityManager = $entityManager;
        $this->eventoManager = $eventoManager;
    }
    
    
    public function indexAction()
    {
        return $this->procesarIndexAction();
             
    }
    
    private function procesarIndexAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_VENTA'] = $parametros;
        }
        if (!is_null($_SESSION['PARAMETROS_VENTA'])){
          $parametros = $_SESSION['PARAMETROS_VENTA'];
        }
        else{
            $parametros=array();
        }
        $paginator = $this->eventoManager->getEventosFiltrados($parametros);
        $total = $this->eventoManager->getTotalFiltrados($parametros);
        $mensaje = "";
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        return new ViewModel([
            'eventos' => $paginator,
            'mensaje' => $mensaje,
            'parametros' =>$parametros,
            'total' => $total,

        ]);
    }
    
    
     public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $evento = $this->eventoManager->getEventoId($id);
        $form = $this->eventoManager->getFormForEvento($evento);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->eventoManager->formValid($form, $data)) {
                    $this->eventoManager->updateEvento($evento, $form);
                    return $this->redirect()->toRoute('application', ['action' => 'view']);
                }
            } else {
                $this->eventoManager->getFormEdited($form, $evento);
            }
            return new ViewModel(array(
                'evento' => $evento,
                'form' => $form
            ));
        }
    }
    
  
}