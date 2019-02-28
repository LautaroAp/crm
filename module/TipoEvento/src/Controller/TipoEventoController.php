<?php

/**
 * Esta clase es el controlador de la entidad TipoEvento.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace TipoEvento\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class TipoEventoController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * TipoEvento manager.
     * @var User\Service\TipoEventoManager 
     */
    protected $tipoeventoManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $eventoManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $categoriaManager;

    public function __construct($entityManager, $tipoeventoManager, $eventoManager, $categoriaManager) {
        $this->entityManager = $entityManager;
        $this->tipoeventoManager = $tipoeventoManager;
        $this->eventoManager = $eventoManager;
        $this->categoriaManager = $categoriaManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }


    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function getRuta($tipo){
        if ($tipo== "cliente"){
            return "/cliente";
        }
        if ($tipo =="proveedor"){
            return "/proveedor";
        }
    }
    private function procesarAddAction() {
        $form = $this->tipoeventoManager->createForm();
        $tipoPersona = $this->params()->fromRoute('tipo');
        $url = $this->getRuta($tipoPersona);
        $limite= $this->getAnterior();
        $this->prepararBreadcrumbs("Listado", $url, $limite);
        $paginator = $this->tipoeventoManager->getTabla($tipoPersona);
        $categorias= $this->tipoeventoManager->getCategoriaEventos();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->tipoeventoManager->addTipoEvento($data, $tipoPersona);
            return $this->redireccionar($tipoPersona);
        }
        $_SESSION['TIPOEVENTO']['TIPO'] = $tipoPersona;
        return new ViewModel([
            'form' => $form,
            'tipoeventos_pag' => $paginator,
            'tipoPersona'=> $tipoPersona,
            'categoriaeventos' => $categorias,
        ]);
    }

    private function redireccionar($tipo){
        if (strtoupper($tipo)==strtoupper("proveedor")){
            return $this->redirect()->toRoute('gestionProveedores/gestionEventosProveedores/tipoeventoProveedor', ['tipo'=>'proveedor']);
        }elseif (strtoupper($tipo)==strtoupper("cliente")){
            return $this->redirect()->toRoute('gestionClientes/gestionEventosClientes/tipoeventoCliente', ['tipo'=>'cliente']);
        }else{
            return $this->redirect()->toRoute('home');
        }
    }

    
    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id');
        $tipoevento = $this->tipoeventoManager->getTipoEventoId($id);
        $tipoPersona = $this->params()->fromRoute('tipo');
        $url = $this->getRuta($tipoPersona);
        $this->prepararBreadcrumbs("Editar","/edit/".$id);
        $categorias= $this->tipoeventoManager->getCategoriaEventos();
        $form = $this->tipoeventoManager->getFormForTipoEvento($tipoevento);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->tipoeventoManager->formValid($form, $data)) {
                    $this->tipoeventoManager->updateTipoEvento($tipoevento, $data);
                    return $this->redireccionar($tipoPersona);
                }
            } else {
                $this->tipoeventoManager->getFormEdited($form, $tipoevento);
            }
            $_SESSION['TIPOEVENTO']['TIPO'] = $tipoPersona;
            return new ViewModel(array(
                'tipoevento' => $tipoevento,
                'categoriaeventos' => $categorias,
                'tipoPersona'=> $tipoPersona,
                'form' => $form
            ));
        }
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    // public function procesarRemoveAction() {
    //     $id = (int) $this->params()->fromRoute('id');
    //     $tipoevento = $this->tipoeventoManager->getTipoEventoId($id);
    //     if ($tipoevento == null) {
    //         $this->reportarError();
    //     } else {
    //         $this->removerDependencias($tipoevento->getTipo(), $id);
    //         $this->eventoManager->eliminarTipoEventos($id);
            
    //         $this->tipoeventoManager->removeTipoEvento($tipoevento);
    //         return $this->redireccionar($tipoevento->getTipo());
    //     }
    // }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $tipoevento = $this->tipoeventoManager->getTipoEventoId($id);
        if ($tipoevento == null) {
            $this->reportarError();
        } else {
                    
            $this->eventoManager->eliminarTipoEventos($id);
            
            $this->tipoeventoManager->removeTipoEvento($tipoevento);
            return $this->redirect()->toRoute('gestionClientes/gestionEventosClientes/tipoeventoCliente');

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
