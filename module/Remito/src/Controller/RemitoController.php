<?php

/**
 * Clase actualmente sin uso
 */

namespace Remito\Controller;

use Transaccion\Controller\TransaccionController;
use Remito\Service\RemitoManager;

use Zend\View\Model\ViewModel;
use DBAL\Entity\BienesTransacciones;

class RemitoController extends TransaccionController{

    /**
     * Remito manager.
     * @var User\Service\RemitoManager 
     */
    protected $remitoManager;
    private $clientesManager;
    private $proveedorManager;
    private $tipo;
    private $bienesTransaccionesManager;
    private $bienesManager;
    private $items;


    public function __construct($remitoManager,$monedaManager, $personaManager, $clientesManager, $proveedorManager,
                                $bienesTransaccionesManager, $bienesManager, $formaPagoManager, $formaEnvioManager, $ivaManager) {
        parent::__construct($remitoManager, $personaManager,  $monedaManager,$ivaManager, $formaPagoManager, $formaEnvioManager);
        $this->clientesManager = $clientesManager;
        $this->proveedorManager = $proveedorManager;
        $this->remitoManager = $remitoManager;
        $this->bienesTransaccionesManager = $bienesTransaccionesManager;
        $this->bienesManager = $bienesManager;
        // $this->itemsSeteados="";
    }

    public function indexAction() {

    }

   
    private function getTipo(){
        return "remito";
    }

    public function addAction() {
        $json="[]";
        if (isset($_SESSION['TRANSACCIONES']['REMITO'])) {
            $json = $_SESSION['TRANSACCIONES']['REMITO'];

        }
        // Obtengo todos los Bienes
        $bienes = $this->bienesManager->getBienes();

        // Creo JSON con Nombres de todos los Productos y Servicios
        $json_bienes = "";

        // $response[] = array("value"=>"1","label"=>"Soporte");
        foreach ($bienes as $bien) {
            $json_bienes .= $bien->getJsonBien() . ',';
        }

        $json_bienes = substr($json_bienes, 0, -1);
        $json_bienes = '[' . $json_bienes . ']';

        $id_persona = $this->params()->fromRoute('id');
        $persona = $this->personaManager->getPersona($id_persona);
        $tipoPersona = null;

        if ($persona->getTipo() == "CLIENTE") {
            $tipoPersona = $this->clientesManager->getClienteIdPersona($id_persona);
        } elseif ($persona->getTipo() == "PROVEEDOR") {
            $tipoPersona = $this->proveedorManager->getProveedorIdPersona($id_persona);
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $data['persona'] = $persona;
            $this->remitoManager->add($data);
            if ($persona->getTipo() == "CLIENTE") {
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            } else {
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }
        $numTransacciones = $this->remitoManager->getTotalTransacciones() + 1;
        $numRemito = $this->remitoManager->getTotalRemitos() + 1;
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $ivasJson = $this->getJsonIvas();
        $pedidos = $this->remitoManager->getTransaccionesPersonaTipo($persona->getId(),"PEDIDO");
        $jsonPedidos = $this->getJsonFromObjectList($pedidos);
        $this->reiniciarParams();
        return new ViewModel([
            // 'items' => $items,
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numRemito' => $numRemito,
            'json' => $json,
            'json_bienes' => $json_bienes,
            'formasPagoJson' => $formasPagoJson,
            'monedasJson' => $monedasJson,
            'ivasJson' => $ivasJson,
            'formasEnvioJson'=>"[]",
            'transaccionJson'=>"[]",
            'pedidosJson' => $jsonPedidos,
            'itemsTransaccionJson' =>"[]",

        ]);
    }

    public function addItemAction() {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $this->procesarAddAction($data);
            $this->redirect()->toRoute('home');
        }
        return new ViewModel();
    }


    public function editAction() {
        $id_transaccion = $this->params()->fromRoute('id');
        $remito = $this->remitoManager->getRemitoFromTransaccionId($id_transaccion);
        $items = array();

        if (!is_null($remito)) {
            $items = $remito->getTransaccion()->getBienesTransacciones();
        }
        $json = "";
        //SI HAY ITEMS CARGADOS EN LA SESION LOS TOMO DE AHI 
        if ((isset($_SESSION['TRANSACCIONES']['REMITO']))){
            $json = $_SESSION['TRANSACCIONES']['REMITO'];
        }
        //SINO LOS TOMO DEL REMITO Y GUARDO ESO EN LA SESION PARA CONTINUAR TRABAJANDO CON LA SESION
        else{
            $json = $this->getJsonFromObjectList($items);
            $_SESSION['TRANSACCIONES']['REMITO'] = $json;
        }
       
        $persona = $remito->getTransaccion()->getPersona();
        $tipoPersona = null;
        // Obtengo todos los Bienes
        $bienes = $this->bienesManager->getBienes();

        // Creo JSON con Nombres de todos los Productos y Servicios
        $json_bienes = "";

        // $response[] = array("value"=>"1","label"=>"Soporte");
        foreach ($bienes as $bien) {
            $json_bienes .= $bien->getJsonBien() . ',';
        }
        $json_bienes = substr($json_bienes, 0, -1);
        $json_bienes = '[' . $json_bienes . ']';

        if ($persona->getTipo() == "CLIENTE") {
            $tipoPersona = $this->clientesManager->getClienteIdPersona($persona->getId());
        } elseif ($persona->getTipo() == "PROVEEDOR") {
            $tipoPersona = $this->proveedorManager->getProveedorIdPersona($persona->getId());
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $data['persona'] = $persona;
            $this->remitoManager->edit($remito, $data);
            $url = $data['url'];
            if ($persona->getTipo() == "CLIENTE") {
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            } else {
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }
        $numTransacciones = $remito->getTransaccion()->getNumero();
        $numRemito = $remito->getNumero();
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $ivasJson = $this->getJsonIvas();
        $transaccion = $remito->getTransaccion();
        $transaccionJson = $remito->getTransaccion()->getJSON();
        $pedidos = $this->remitoManager->getTransaccionesPersonaTipo($persona->getId(),"PEDIDO");
        $pedidoPrevio = $this->remitoManager->getPedidoPrevioFromTransaccion($remito->getTransaccion()->getTransaccionPrevia());
        $jsonPedidos = $this->getJsonFromObjectList($pedidos);
        $this->reiniciarParams();
        return new ViewModel([
            // 'items' => $items,
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numRemito' => $numRemito,
            'json' => $json,
            'json_bienes' => $json_bienes,
            'formasPagoJson' => $formasPagoJson,
            'monedasJson' => $monedasJson,
            'formasEnvioJson'=>"[]",
            'transaccionJson' => $transaccionJson,
            'transaccion' => $transaccion,
            'ivasJson' => $ivasJson,
            'pedidosJson' => $jsonPedidos,
            'itemsTransaccionJson' =>"[]",
            'pedidoPrevio'=> $pedidoPrevio,
        ]);
    }

    public function setItemsAction(){
        $items = $_POST['json'];
        $_SESSION['TRANSACCIONES']['REMITO'] = $items;
    }

    public function eliminarItemAction(){
        $this->layout()->setTemplate('layout/nulo');
        $pos = $this->params()->fromRoute('id');
        $id = $this->params()->fromRoute('id2');
        $array = json_decode($_SESSION['TRANSACCIONES']['REMITO']);
        array_splice($array, $pos, 1);
        $json = json_encode($array);
        $_SESSION['TRANSACCIONES']['REMITO']= $json;
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }

    // PDF
   public function pdfAction() {
    $this->layout()->setTemplate('layout/nulo');
    $id_transaccion = $this->params()->fromRoute('id');
    $remito = $this->remitoManager->getRemitoFromTransaccionId($id_transaccion);
    $items = array();

    if (!is_null($remito)) {
        $items = $remito->getTransaccion()->getBienesTransacciones();
    }
   
    $json = "";

    $items_array = $this->getItemsArray($items);
    foreach ($items_array as $array) {
        $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
        $json .= $item->getJson() . ',';
        
    }
    $json = substr($json, 0, -1);
    $json = '[' . $json . ']';     
   
    $persona = $remito->getTransaccion()->getPersona();
    $tipoPersona = null;

    $numTransacciones = $remito->getTransaccion()->getNumero();
    $numRemito = $remito->getNumero();
    $monedasJson = $this->getJsonMonedas();
    $formasPagoJson = $this->getJsonFormasPago();
    $formasEnvioJson = $this->getJsonFormasEnvio();
    $ivasJson = $this->getJsonIvas();
    $transaccion = $remito->getTransaccion();
    $transaccionJson = $remito->getTransaccion()->getJSON();
    $presupuestos = $this->remitoManager->getTransaccionesPersonaTipo($persona->getId(),"PRESUPUESTO");
    $jsonPrespuestos = $this->getJsonFromObjectList($presupuestos);
    $this->reiniciarParams();
    return new ViewModel([
        'persona' => $persona,
        'tipoPersona' => $tipoPersona,
        'numTransacciones' => $numTransacciones,
        'numRemito' => $numRemito,
        'json' => $json,
        'formasPagoJson' => $formasPagoJson,
        'formasEnvioJson' => $formasEnvioJson,
        'monedasJson' => $monedasJson,
        'remito' => $remito,
        'transaccion' => $transaccion,
        'transaccionJson' => $transaccionJson,
        'ivasJson' => $ivasJson,
        'presupuestosJson' => $jsonPrespuestos,
        'itemsTransaccionJson' =>"[]",
        ]);
    }


    public function getItemsTransaccionAction(){
        $this->layout()->setTemplate('layout/nulo');
        $idTransaccion = $this->params()->fromRoute('id');
        $transaccion = $this->remitoManager->getTransaccionId($idTransaccion);
        $items = $transaccion->getBienesTransacciones();
        $itemsTransaccionJson = $this->getJsonFromObjectList($items);
        $view = new ViewModel(['itemsTransaccionJson'=>$itemsTransaccionJson]);
        $view->setTerminal(true);
        return $view;
   }
   
   public function getItemsPreviosAction(){
        $this->layout()->setTemplate('layout/nulo');
        $numPedido = $this->params()->fromRoute('id');
        $pedido = $this->remitoManager->getPedidoPrevio($numPedido);
        $itemsTransaccionJson="[]";
        if ($pedido!=null){
            $transaccion = $pedido->getTransaccion();
            $items = $transaccion->getBienesTransacciones();
            $itemsTransaccionJson = $this->getJsonFromObjectList($items);
        }
        $view = new ViewModel(['itemsTransaccionJson'=>$itemsTransaccionJson]);
        $view->setTerminal(true);
        return $view;
    }   

    public function cambiarEstadoAction(){
        $this->layout()->setTemplate('layout/nulo');
        $idTransaccion = $this->params()->fromRoute('id');
        $estado= $this->params()->fromRoute('id2');
        $this->remitoManager->cambiarEstadoTransaccion($idTransaccion, $estado);
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }
}
