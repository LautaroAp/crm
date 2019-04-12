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
        return ((isset($params['tipo']) and $params['tipo']!="-1") or (isset($params['nombre'])));
    }

    private function agregar($params){
        if ((isset($params['cantidad']) and isset($params['subtotal'])) and isset($params['idbien'])){
            return($params['cantidad']>0);          
        }
        else return false;
    }

    public function indexAction() {
        $request = $this->getRequest();
        $transaccion =$this->params()->fromRoute('transaccion');
        $accion = $this->params()->fromRoute('accion');
        $id_persona = $this->params()->fromRoute('id');
        $ivas = $this->ivaManager->getIvas();

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

        $parametros = $this->limpiarParametros($parametros);
        if (($this->agregar($parametros)) and (!$this->busqueda($parametros))){
            return $this->addItem($parametros, $transaccion, $id_persona, $accion);
        }
        $bienes = $this->bienesManager->getBienesFiltrados2($parametros);

        return new ViewModel([
            'bienes' => $bienes,
            'ivas' => $ivas,
            'transaccion' => $transaccion,
            'id_persona' => $id_persona
        ]);
    }

    private function addItem($parametros, $transaccion, $id_persona, $accion){
        $bienTransaccion = new BienesTransacciones();
        $bien = $this->bienesManager->getBienId($parametros['idbien']);
        $bienTransaccion->setBien($bien);
        $bienTransaccion->setDescuento($parametros['descuento']);
        $bienTransaccion->setCantidad($parametros['cantidad']);
        $iva = $this->ivaManager->getIva($parametros['iva']);
        $bienTransaccion->setIva($iva);
        $subtotal = $parametros['subtotal'];
        if ($subtotal[0]=="$"){
            $subtotal = substr($subtotal, 2);
        }
        $bienTransaccion->setSubtotal($subtotal); 
        $transaccionUpper =strtoupper($transaccion);

        if (!isset($_SESSION['TRANSACCIONES'][strtoupper($transaccion)])){
            $_SESSION['TRANSACCIONES'][strtoupper($transaccion)] = "[]";
        }

        $json = $_SESSION['TRANSACCIONES'][strtoupper($transaccion)];
        //le quito al json el cierre de corchete
        $json = substr($json, 0, -1);
        $newJson = $bienTransaccion->getJSON();
        $json .= ", " . $newJson . "]";
        // $array = json_decode($json, true);
        // $btjson= $bienTransaccion->toArray();
        // print_r($array);
        // array_push($array, $btjson);
        // $json = json_encode($array);
        // print_r($json);
        $_SESSION['TRANSACCIONES'][strtoupper($transaccion)]=$json;
        $ruta= $transaccion."/".$accion;
        return $this->redirect()->toRoute($ruta,['id'=>$id_persona]);
    }
}
