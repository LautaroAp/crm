<?php

namespace Application\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class IndexController extends HuellaController {

    private $entityManager;
    protected $result;

    public function __construct($entityManager, $clientesManager) {
        $this->entityManager = $entityManager;
        $this->clientesManager = $clientesManager;
    }

    // public function indexAction() {
    //     $label = "Home";
    //     $url = "";
    //     $this->reiniciarBreadcrumbs($label, $url);
    //     $this->reiniciarParametros();
    //     $_SESSION['MENSAJES'] = array();
    //     $_SESSION['CATEGORIA'] = array();
    //     $_SESSION['TIPOEVENTO'] = array();
    //     // $this->layout()->setTemplate('layout/simple');
    //     return new ViewModel();
    // }

    public function viewAction() {
        return new ViewModel();
    }

    public function menuAction(){
        $label = "Home";
        $url = "/";
        $this->reiniciarBreadcrumbs($label, $url);
        $this->reiniciarParametros();
        $_SESSION['MENSAJES'] = array();
        $_SESSION['CATEGORIA'] = array();
        $_SESSION['TIPOEVENTO'] = array();        
        return new ViewModel();
    }

    private function reiniciarParametros(){
        $_SESSION['PARAMETROS_VENTA'] = array();
        $_SESSION['PARAMETROS_CLIENTE'] = array();
        $_SESSION['PARAMETROS_CLIENTE_INACTIVO'] = array();
    }

    public function gestionClientesAction() {
        $label = "Clientes";
        $url = "/clientes";
        $limite = "Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionActividadesClientesAction() {
        $label = "Actividades";
        $url = "/actividades";
        $limite = "Clientes";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionProveedoresAction() {
        $label = "Proveedores";
        $url = "/proveedores";       
        $limite = "Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionActividadesProveedoresAction() {
        $label = "Actividades";
        $url = "/actividades";
        $limite = "Proveedores";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

    // public function gestionProductosProveedorAction() {
    //     $this->reiniciarParametros();
    //     return new ViewModel();
    // }

    // public function gestionServiciosProveedorAction() {
    //     $this->reiniciarParametros();
    //     return new ViewModel();
    // }

    public function gestionEmpresaAction() {
        $label = "Empresa";
        $url = "/empresa";
        $limite = "Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionProductosServiciosAction() {
        $label = "Productos y Servicios";
        $url = "/productosservicios";
        $limite = "Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionLicenciasAction() {
        $label = "Licencias";
        $url = "/licencias";
        $limite = "Productos y Servicios";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionProductosAction() {
        $label = "Productos";
        $url = "/productos";
        $limite = "Productos y Servicios";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function gestionServiciosAction() {
        $label = "Servicios";
        $url = "/servicios";
        $limite = "Productos y Servicios";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

    public function herramientasAction() {
        $label = "Herramientas";
        $url = "/herramientas";
        $limite ="Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

    // public function utilidadesAction() {
    //     $this->reiniciarParametros();
    //     return new ViewModel();
    // }

    // public function gestionAction() {
    //     $this->reiniciarParametros();
    //     return new ViewModel();
    // }     
}
