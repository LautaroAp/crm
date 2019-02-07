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
        $this->reiniciarParametros();
        $_SESSION['MENSAJES'] = array();
        $_SESSION['CATEGORIA'] = array();
        $_SESSION['TIPOEVENTO'] = array();
        // $this->layout()->setTemplate('layout/simple');
        return new ViewModel();
    }

    public function viewAction() {
        return new ViewModel();
    }

    public function menuAction(){
        return new ViewModel();
    }

    private function reiniciarParametros(){
        $_SESSION['PARAMETROS_VENTA'] = array();
        $_SESSION['PARAMETROS_CLIENTE'] = array();
        $_SESSION['PARAMETROS_CLIENTE_INACTIVO'] = array();
    }

    public function gestionClientesAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionActividadesClientesAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionProveedoresAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionActividadesProveedoresAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionProductosProveedorAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionServiciosProveedorAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionEmpresaAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionProductosServiciosAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionLicenciasAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionProductosAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionServiciosAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function herramientasAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function utilidadesAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionAction() {
        $this->reiniciarParametros();
        return new ViewModel();
    }     
}
