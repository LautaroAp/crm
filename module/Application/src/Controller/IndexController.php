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

    protected function reiniciarParametros($arreglo=null){
        $_SESSION['PARAMETROS_VENTA'] = array();
        $_SESION['EVENTO'] = array();
        $_SESSION['BIENES'] = array();
        $_SESSION['PARAMETROS_BIENES'] = array();
        $_SESSION['PARAMETROS_CLIENTE'] = array();
        $_SESSION['PARAMETROS_PROVEEDOR'] = array();
        $_SESSION['PARAMETROS_CLIENTE_INACTIVO'] = array();
        $_SESSION['TRANSACCIONES'] = array();

    }

    public function gestionClientesAction() {
        $label = "Clientes";
        $url = "/clientes";
        $limite = "Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }

    public function gestionEventosClientesAction() {
        $label = "Eventos";
        $url = "/actividades";
        $limite = "Clientes";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }

    public function gestionProveedoresAction() {

        $label = "Proveedores";
        $url = "/proveedores";       
        $limite = "Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }

    public function gestionEventosProveedoresAction() {
        $label = "Eventos";
        $url = "/actividades";
        $limite = "Proveedores";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }

    public function gestionEmpresaAction() {
        $label = "Empresa";
        $url = "/empresa";
        $limite = "Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }

    public function gestionProductosServiciosAction() {
        $label = "Productos y Servicios";
        $url = "/productosservicios";
        $limite = "Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }

    public function gestionLicenciasAction() {
        $label = "Licencias";
        $url = "/licencias";
        $limite = "Productos y Servicios";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }

    public function gestionProductosAction() {
        $label = "Productos";
        $url = "/productos";
        $limite = "Productos y Servicios";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }

    public function gestionServiciosAction() {
        $label = "Servicios";
        $url = "/servicios";
        $limite = "Productos y Servicios";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }

    public function herramientasAction() {
        $label = "Herramientas";
        $url = "/herramientas";
        $limite ="Home";
        $this->prepararBreadcrumbs($label, $url, $limite);
        $this->reiniciarParametros();
        $volver = $this->getUltimaUrl();
        return new ViewModel(
            ['volver' => $volver]
        );
    }
 
}
