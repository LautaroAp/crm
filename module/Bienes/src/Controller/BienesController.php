<?php

namespace Bienes\Controller;

use Zend\View\Model\ViewModel;


class BienesVentaController extends BienesController
{


    /**
     * Bienes manager.
     * @var User\Service\eventoVentaManager 
     */
    protected $bienesManager;


    protected $tipoBienesManager;
    
    public function __construct($bienesManager){
        $this->bienesManager = $bienesManager;
    }
    
    
    public function indexAction()
    {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $request = $this->getRequest();
       //SE OBTIENE EL TIPO DE BIEN LA RUTA POR SI SE LO LLAMA DE CLIENTE/PROVEEDOR
        $tipo= $this->params()->fromRoute('tipo');
        if (isset($tipo)){
            //si llego una persona por ruta se la guarda en la sesion para paginator
            $_SESSION['BIENES']['TIPO'] = $tipo;
            $this->prepararBreadcrumbs("Bienes de Cambio", "/bienes/".$tipo);
        }
        else{
            $_SESSION['BIENES']['TIPO'] = "todos";
            $this->prepararBreadcrumbs("Bienes de Cambio", "/bienes");
        }
        if ($request->isPost()) {
            //SI SE COMPLETO EL FORMULARIO DE BUSQUEDA TOMO ESOS PARAMETROS Y LOS GUARDO EN LA SESION 
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_BIENES'] = $parametros;
        }
        if (!is_null($_SESSION['PARAMETROS_BIENES'])) {
            //SI HAY PARAMETROS GUARDADOS EN LA SESION TOMAR ESOS PARAMETROS 
            $parametros = $_SESSION['PARAMETROSBIENES'];
        } else {
            //SI NO HAY PARAMETROS CREAR NUEVOS
            $parametros = array();
        }
        if (($_SESSION['BIENES']['TIPO'] == "todos") and isset($parametros['tipo'])){
            //SI LLEGO DESDE EMPRESA TOMO EL TIPO DE PERSONA DEL FORMULARIO
            $tipoBien = $parametros['tipo_bien'];       
        }
        else {
            //SI LLEGO DESDE  TOMO EL TIPO DE PERSONA DE LA RUTA
            $tipoBien= $tipo;
            $parametros['tipo_bien'] = $tipo;
        }
        if (($tipoBien == '-1') and ($_SESSION['BIENES']['TIPO'] == "todos")){
            //SI SE SELECCIONO "TODOS" (MOSTRAR PRODUCTOS, LICENCIAS Y SERVICIOS)
            $tipoBien = null;
            unset($parametros['tipo']);
        }
        $paginator = $this->bienesManager->getBienesFiltrados($parametros);
        $total = $this->bienesManager->getTotalFiltrados($parametros);
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage($this->getElemsPag());
        return new ViewModel([
            'bienes' => $paginator,
            'parametros' => $parametros,
            'tipo' => $tipo,
            'tipo_bien' =>$tipoBien,
            'total' => $total,
        ]);
    }
}
