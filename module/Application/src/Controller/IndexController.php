<?php

namespace Application\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class IndexController extends HuellaController {

    protected $entityManager;
    protected $result;
    protected $empresaManager;

    public function __construct($entityManager, $empresaManager) {
        $this->entityManager = $entityManager;
        $this->empresaManager = $empresaManager;
    }

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
        $_SESSION['ELEMSPAG'] = $this->empresaManager->getElemsPag();
       
        return new ViewModel();
    }

    private function reiniciarParametros(){
        $_SESSION['PARAMETROS_VENTA'] = array();
        $_SESION['EVENTO'] = array();
        $_SESSION['PARAMETROS_CLIENTE'] = array();
        $_SESSION['PARAMETROS_PROVEEDOR'] = array();
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

    public function gestionEventosClientesAction() {
        $label = "Eventos";
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

    public function gestionEventosProveedoresAction() {
        $label = "Eventos";
        $url = "/actividades";
        $limite = "Proveedores";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        return new ViewModel();
    }

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
 
}
