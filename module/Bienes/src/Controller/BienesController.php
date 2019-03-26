<?php

namespace Bienes\Controller;
use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;
use DBAL\Entity\BienesTransacciones;

class BienesController extends HuellaController
{

   protected $bienesManager;
   private $ivaManager;

    public function __construct($bienesManager,$ivaManager){
        $this->bienesManager = $bienesManager;
        $this->ivaManager= $ivaManager;
    }
        
    private function busqueda($params){
        return ((($params['tipo'])>1) or (isset($params['nombre'])));
    }

    private function agregar($params){

        if ((isset($params['cantidad']) and isset($params['subtotal'])) and isset($params['idbien'])){
            return true;
        }
        else return false;
    }

    public function indexAction() {
        
        $request = $this->getRequest();
       //SE OBTIENE EL TIPO DE BIEN LA RUTA POR SI SE LO LLAMA DE CLIENTE/PROVEEDOR
        $tipo= $this->params()->fromRoute('tipo');
        $transaccion =$this->params()->fromRoute('transaccion');
        $id_persona = $this->params()->fromRoute('id');
        $ivas = $this->ivaManager->getIvas();
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
            //SI EL TIPO DE LA RUTA NO ES NULO
            $tipoBien= $tipo;
            $parametros['tipo'] = $tipo;
        }
        if (($tipoBien == '-1') and ($_SESSION['BIENES']['TIPO'] == "todos")){
            //SI SE SELECCIONO "TODOS" (MOSTRAR PRODUCTOS, LICENCIAS Y SERVICIOS)
            $tipoBien = null;
            unset($parametros['tipo']);
        }
        if ($this->agregar($parametros)){
            return $this->addItem($parametros, $transaccion, $id_persona);
        }
        $bienes = $this->bienesManager->getBienesFiltrados2($parametros);
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }

        return new ViewModel([
            'bienes' => $bienes,
            'ivas' => $ivas,
            // 'parametros' => $parametros,
            'tipo' => $tipo,
            // 'tipo_bien' =>$tipoBien,
            'transaccion' => $transaccion,
            'id_persona' => $id_persona
        ]);
    }

    private function addItem($parametros, $transaccion, $id_persona){
        $bienTransaccion = new BienesTransacciones();
        $bien = $this->bienesManager->getBienId($parametros['idbien']);
        $bienTransaccion->setBien($bien);
        $bienTransaccion->setDescuento($parametros['descuento']);
        $bienTransaccion->setCantidad($parametros['cantidad']);
        $iva = $this->ivaManager->getIva($parametros['iva']);
        // print_r($iva); die(); // No aplica el IVA si se deja el que carga por defecto (hay que cambiarlo)
        $bienTransaccion->setIva($iva);
        $bienTransaccion->setSubtotal($parametros['subtotal']);
        if (!isset($_SESSION['TRANSACCIONES']['PEDIDO'])){
            $_SESSION['TRANSACCIONES']['PEDIDO'] = array();
        }
        array_push($_SESSION['TRANSACCIONES']['PEDIDO'], $bienTransaccion);
        $ruta= $transaccion."/agregar";
        print_r("agrego a transacciones pedido el item ");
        print_r(COUNT($_SESSION['TRANSACCIONES']['PEDIDO']));
        // die();
        return $this->redirect()->toRoute($ruta,['id'=>$id_persona]);
    }
}
