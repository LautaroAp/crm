<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Evento\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use DBAL\Entity\Evento;
use DBAL\Entity\Cliente;
use DBAL\Entity\TipoEvento;
use Zend\Filter\StringToLower;

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
        $tipo= $_SESSION['TIPOEVENTO']['TIPO']; 
        if ($tipo=="CLIENTE"){
            $this->prepararBreadcrumbs("Agregar Evento", "/evento/add/cliente/".$Id, "Ficha Cliente");

        }
        $persona= $this->personaManager->getPersona($Id);
        $cliente = $this->clienteManager->getClienteIdPersona($persona->getId());
        $tipoEventos = $this->getArrayTipos($tipo);
        $form = $this->eventoManager->createForm($tipoEventos);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->eventoManager->addEvento($data,$persona);
                return $this->redireccionar($tipo, $Id);
        }
        return new ViewModel([
            'form' => $form,
            'persona' => $persona,
            'tipoPersona'=>$tipo,
            'tipos' => $tipoEventos,
        ]);
    }

    private function redireccionar($tipo, $id){
        if (strtoupper($tipo)=="CLIENTE"){
            return $this->redirect()->toRoute('clientes/listado/ficha', ['action' => 'ficha', 'id' =>$id]);
        }elseif (strtoupper($tipo)=="PROVEEDOR"){
            return $this->redirect()->toRoute('proveedores/listado/ficha', ['action' => 'ficha', 'id' =>$id]);
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
                return $this->redirect()->toRoute('clientes/listado/ficha', ['action' => 'ficha', 'id' =>$Id]);
        }
        return new ViewModel([
            'form' => $form,
            'persona' => $persona,
            'tipos' => $tipoEventos,
        ]);
    }

    // private function procesarAddProveedor($Id, $tipo){
    //     $proveedor = $this->proveedorManager->getProveedor($Id);
    //     $id_persona= $proveedor->getPersona()->getId();
    //     $tipoEventos = $this->getArrayTipos($tipo);
    //     $form = $this->eventoManager->createForm($tipoEventos);
    //     if ($this->getRequest()->isPost()) {
    //         $data = $this->params()->fromPost();
    //         $this->eventoManager->addEvento($data, $proveedor->getPersona(), $tipo_persona);
    //         return $this->redirect()->toRoute('proveedores/listado/ficha', ['action' => 'ficha', 'id' =>$id_persona]);
    //     }
    //     return new ViewModel([
    //         'form' => $form,
    //         'cliente' => $proveedor,
    //         'tipos' => $tipoEventos,
    //     ]);
    // }


    // public function getTablaFiltrado($filtro) {
    //     $listaEventos = $this->getSearch($filtro);
    //     return ($listaEventos);
    // }

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
