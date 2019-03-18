<?php

namespace Bienes\Controller;
use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;


class BienesController extends HuellaController
{

   protected $bienesManager;

    public function __construct($bienesManager){
        $this->bienesManager = $bienesManager;
    }
    
    
    // public function indexAction()
    // {
    //     return $this->procesarIndexAction();
    // }

    public function indexAction() {
        $request = $this->getRequest();
       //SE OBTIENE EL TIPO DE BIEN LA RUTA POR SI SE LO LLAMA DE CLIENTE/PROVEEDOR
        $tipo= $this->params()->fromRoute('tipo');
        if (isset($tipo)){
            //si llego un tipo de bien por ruta se la guarda en la sesion para paginator
            $_SESSION['BIENES']['TIPO'] = $tipo;
        }
        else{
            $_SESSION['BIENES']['TIPO'] = "todos";
        }
        if ($request->isPost()) {
            //SI SE COMPLETO EL FORMULARIO DE BUSQUEDA TOMO ESOS PARAMETROS Y LOS GUARDO EN LA SESION 
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_BIENES'] = $parametros;
        }
        if (isset($_SESSION['PARAMETROS_BIENES'])) {
            //SI HAY PARAMETROS GUARDADOS EN LA SESION TOMAR ESOS PARAMETROS 
            $parametros = $_SESSION['PARAMETROS_BIENES'];
        } else {
            //SI NO HAY PARAMETROS CREAR NUEVOS
            $parametros = array();
        }
        if (($_SESSION['BIENES']['TIPO'] == "todos") and isset($parametros['tipo'])){
            //SI LLEGO DESDE EMPRESA TOMO EL TIPO DE PERSONA DEL FORMULARIO
            $tipoBien = $parametros['tipo'];       
        }
        else {
            //SI EL TIPO DE LA RUTA NO ES NILO
            $tipoBien= $tipo;
            $parametros['tipo'] = $tipo;
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
        $paginator->setCurrentPageNumber((int) $page)->setItemCountPerPage($this->getElemsPag());
        return new ViewModel([
            'bienes' => $paginator,
            'parametros' => $parametros,
            'tipo' => $tipo,
            'tipo_bien' =>$tipoBien,
            'total' => $total,
        ]);
    }
}
