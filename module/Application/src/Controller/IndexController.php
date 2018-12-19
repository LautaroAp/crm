<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    private $entityManager;
    private $profesionclienteManager;
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
        $this->layout()->setTemplate('layout/simple');
        return new ViewModel(array('titulo' => 'Hola mundo',));
    }

    public function viewAction() {
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
