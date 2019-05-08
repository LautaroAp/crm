<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Evento\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class EventoController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Evento manager.
     * @var User\Service\EventoManager 
     */
    protected $eventoManager;
    
    protected $clienteManager;
    protected $proveedorManager;
    protected $tipoEventoManager;
    private $personaManager;


    public function __construct($entityManager, $eventoManager, $clienteManager, $proveedorManager,
     $tipoEventoManager, $personaManager) {
        $this->entityManager = $entityManager;
        $this->eventoManager = $eventoManager;
        $this->clienteManager= $clienteManager;
        $this->proveedorManager= $proveedorManager;
        $this->tipoEventoManager= $tipoEventoManager;
        $this->personaManager= $personaManager;
    }

    //esta funcion recibe un tipo de persona y le pide al manager los tipos de eventos 
    //correspondientes segun si el tipo de persona es cliente o proveedor
    private function getArrayTipos($tipo) {
        $tipos = $this->tipoEventoManager->getTipoEventos($tipo);
        return $tipos;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $paginator = $this->eventoManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'eventos' => $paginator,
            'volver' => $volver,
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $Id = (int) $this->params()->fromRoute('id', -1);
        $tipo = $this->params()->fromRoute('tipo');
        if ($tipo=="cliente"){
            $this->prepararBreadcrumbs("Agregar Evento", "/evento/add/cliente/".$Id, "Ficha Cliente");
        }
        elseif ($tipo== "proveedor"){
            $this->prepararBreadcrumbs("Agregar Evento", "/evento/add/proveedor/".$Id, "Ficha Proveedor");
        }
        $persona= $this->personaManager->getPersona($Id);
        $tipoEventos = $this->getArrayTipos(strtoupper($tipo));
        $form = $this->eventoManager->createForm($tipoEventos);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->eventoManager->addEvento($data,$persona);
                return $this->redireccionar($tipo, $Id);
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'form' => $form,
            'persona' => $persona,
            'tipoPersona'=>$tipo,
            'tipos' => $tipoEventos,
            'volver' => $volver,
        ]);
    }

    private function redireccionar($tipo, $id){
        if (strtoupper($tipo)=="CLIENTE"){
            return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' =>$id]);
        }elseif (strtoupper($tipo)=="PROVEEDOR"){
            return $this->redirect()->toRoute('proveedores/ficha', ['action' => 'ficha', 'id' =>$id]);
        }
        return $this->redirect()->toRoute('home');
    }



    private function procesarAddCliente($Id, $tipo){
        $persona= $this->personaManager->getPersona($Id);
        $tipoEventos = $this->getArrayTipos($tipo);
        $form = $this->eventoManager->createForm($tipoEventos);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->eventoManager->addEvento($data,$persona);
                return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' =>$Id]);
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'form' => $form,
            'persona' => $persona,
            'tipos' => $tipoEventos,
            'volver' => $volver,
        ]);
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
            $volver = $this->getUltimaUrl();
            return new ViewModel(array(
                'evento' => $evento,
                'form' => $form,
                'tipos' => $this->tipos,
                'volver' => $volver,
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
