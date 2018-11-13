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
use DBAL\Entity\Evento;
use Evento\Form\EventoForm;
use DBAL\Entity\Cliente;
use DBAL\Entity\TipoEvento;

class EventoController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Evento manager.
     * @var User\Service\EventoManager 
     */
    protected $eventoManager;
    private $tipos;

    public function __construct($entityManager, $eventoManager) {
        $this->entityManager = $entityManager;
        $this->eventoManager = $eventoManager;
        $this->tipos = $this->getArrayTipos();
    }

    private function getArrayTipos() {
        $tipos = $this->entityManager->getRepository(TipoEvento::class)->findAll();
        $array = array();
        foreach ($tipos as $tipo) {
            $array2 = array($tipo->getId() => $tipo->getNombre());
            $array = $array + $array2;
        }
        return $array;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {

        $paginator = $this->eventoManager->getTabla();
        $mensaje = "";
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        return new ViewModel([
            'eventos' => $paginator,
            'mensaje' => $mensaje
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $Id = (int) $this->params()->fromRoute('id', -1);
        $cliente = $this->entityManager->getRepository(Cliente::class)->findOneBy(['Id' => $Id]);
        $form = $this->eventoManager->createForm($this->tipos);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->eventoManager->addEvento($data);  
            return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $data['id_cliente']]);
        }
        return new ViewModel([
            'form' => $form,
            'cliente' => $cliente,
            'tipos' => $this->tipos,
        ]);
    }

    public function getTablaFiltrado($filtro) {
        $listaEventos = $this->getSearch($filtro);
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Evento::class));
        $paginator = new Paginator($adapter);

        return ($listaEventos);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $evento = $this->eventoManager->getEventoId($id);
        
        $form = $this->eventoManager->createForm($this->tipos);
        
       // $form = $this->eventoManager->getFormForEvento($evento);
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
                'form' => $form,
                'tipos' => $this->tipos
            ));
        }
    }

    public function removeAction() {

        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $evento = $this->eventoManager->getEventoId($id);

        if ($evento == null) {
            $this->reportarError();
        } else {
            $this->eventoManager->removeEvento($evento);
            return $this->redirect()->toRoute('application', ['action' => 'view']);
        }
    }

    public function viewAction() {
        return new ViewModel();
    }

    private function reportarError() {
        $this->getResponse()->setStatusCode(404);
        return;
    }

}
