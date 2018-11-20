<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ejecutivo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;

use DBAL\Entity\Ejecutivo;
use DBAL\Entity\Evento;

class EjecutivoInactivoController extends EjecutivoController
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;
    

    //private $ejecutivoInactivo;
    
    public function __construct($entityManager, $ejecutivoInactivo)
    {
        $this->entityManager = $entityManager;
        $this->ejecutivoInactivoManager = $ejecutivoInactivo;
    }
    
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }

    public function indexAction()
    {     
        $paginator = $this->ejecutivoInactivoManager->getTabla();
        $mensaje ="";
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        
        return new ViewModel([
            'ejecutivos' => $paginator,
            'mensaje' => $mensaje
        ]);
    }
    
    public function activarAction(){
        $id = $this->params()->fromRoute('id');
        $this->procesarActivar($id);
        $this->redirect()->toRoute('ejecutivos/inactivos');
        return new ViewModel();
    }
    
    private function procesarActivar($id){
        $ejecutivo = $this->ejecutivoInactivoManager->recuperarEjecutivo($id);
        $ejecutivo->activar();
        $this->entityManager->flush();
        $_SESSION['MENSAJES']['ejecutivo_inactivo'] = 1;
        $_SESSION['MENSAJES']['ejecutivo_inactivo_msj'] = 'Ejecutivo dado de Alta correctamente';
    }
   
}
