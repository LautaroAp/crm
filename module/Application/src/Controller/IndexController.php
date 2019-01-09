<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    private $entityManager;
    protected $result;

    public function __construct($entityManager, $clientesManager) {
        $this->entityManager = $entityManager;
        $this->clientesManager = $clientesManager;
    }

    public function indexAction() {
        $_SESSION['PARAMETROS_VENTA'] = array();
        $_SESSION['PARAMETROS_CLIENTE'] = array();
        $_SESSION['PARAMETROS_CLIENTE_INACTIVO'] = array();
        $_SESSION['MENSAJES'] = array();
        $_SESSION['CATEGORIA'] = array();
        $this->layout()->setTemplate('layout/simple');
        return new ViewModel();
    }

    public function viewAction() {
        return new ViewModel();
    }

    public function menuAction(){
        return new ViewModel();
    }

    public function gestionClientesAction() {
        $_SESSION['PARAMETROS_CLIENTE'] = array();
        $_SESSION['PARAMETROS_CLIENTE_INACTIVO'] = array();
        return new ViewModel();
    }

    public function gestionActividadesClientesAction() {
        return new ViewModel();
    }

    public function gestionProveedoresAction() {
        $_SESSION['PARAMETROS_PROVEEDOR'] = array();
        $_SESSION['PARAMETROS_PROVEEDOR_INACTIVO'] = array();
        return new ViewModel();
    }

    public function gestionActividadesProveedoresAction() {
        return new ViewModel();
    }

    public function gestionProductosProveedorAction() {
        return new ViewModel();
    }

    public function gestionServiciosProveedorAction() {
        return new ViewModel();
    }

    public function gestionEmpresaAction() {
        return new ViewModel();
    }

    public function gestionLicenciasEmpresaAction() {
        return new ViewModel();
    }

    public function gestionProductosEmpresaAction() {
        return new ViewModel();
    }

    public function gestionServiciosEmpresaAction() {
        return new ViewModel();
    }

    public function herramientasAction() {
        return new ViewModel();
    }

    public function utilidadesAction() {
        return new ViewModel();
    }

    public function gestionAction() {
        return new ViewModel();
    }  

    public function backupmenuAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $_SESSION['PARAMETROS_BACKUP'] = $data;
            $this->redirect()->toRoute("backup");
        }
        return new ViewModel();
    }

   
}
