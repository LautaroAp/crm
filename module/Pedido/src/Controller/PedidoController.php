<?php

/**
 * Clase actualmente sin uso
 */

namespace Pedido\Controller;

use Transaccion\Controller\TransaccionController;
use Pedido\Service\PedidoManager;

use Zend\View\Model\ViewModel;
use DBAL\Entity\BienesTransacciones;

class PedidoController extends TransaccionController
{

    /**
     * Pedido manager.
     * @var User\Service\PedidoManager 
     */
    protected $pedidoManager;
    private $clientesManager;
    private $proveedorManager;
    private $tipo;
    private $bienesTransaccionesManager;
    private $bienesManager;
    private $items;
    private $formaPagoManager;
    public function __construct(
        $pedidoManager,
        $monedaManager,
        $personaManager,
        $clientesManager,
        $proveedorManager,
        $bienesTransaccionesManager,
        $bienesManager,
        $formaPagoManager, 
        $ivaManager
    ) {
        parent::__construct($pedidoManager, $personaManager,  $monedaManager,$ivaManager);
        $this->clientesManager = $clientesManager;
        $this->proveedorManager = $proveedorManager;
        $this->pedidoManager = $pedidoManager;
        $this->bienesTransaccionesManager = $bienesTransaccionesManager;
        $this->bienesManager = $bienesManager;
        $this->formaPagoManager = $formaPagoManager;
    }

    public function indexAction()
    {

    }


    private function getTipo()
    {
        return "pedido";
    }

    public function addAction()
    {
        $items = array();
        if (isset($_SESSION['TRANSACCIONES']['PEDIDO'])) {
            $items = $_SESSION['TRANSACCIONES']['PEDIDO'];
        }
        $json = "";
        foreach ($items as $array) {
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            $json .= $item->getJson() . ',';
        }
        $json = substr($json, 0, -1);
        $json = '[' . $json . ']';

        // Obtengo todos los Bienes
        $bienes = $this->bienesManager->getBienes();

        // Creo JSON con Nombres de todos los Productos y Servicios
        $json_bienes = "";

        // $response[] = array("value"=>"1","label"=>"Soporte");
        foreach ($bienes as $bien) {
            // $json_bienes .= $bien->getJsonBien() . ',';
            $json_bienes .= $bien->getJsonBienToBT() . ',';
        }
        $json_bienes = substr($json_bienes, 0, -1);
        $json_bienes = '[' . $json_bienes . ']';

        // var_dump(json_decode($json_bienes, true));
        // die();

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
            $this->pedidoManager->add($data);
            if ($persona->getTipo() == "CLIENTE") {
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            } else {
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }
        $numTransacciones = $this->pedidoManager->getTotalTransacciones() + 1;
        $numPedido = $this->pedidoManager->getTotalPedidos() + 1;
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $ivasJson = $this->getJsonIvas();
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numPedido' => $numPedido,
            'json' => $json,
            'json_bienes' => $json_bienes,
            'formasPagoJson' => $formasPagoJson,
            'monedasJson' => $monedasJson,
            'ivasJson' => $ivasJson,
            'formasEnvioJson'=>"[]",
            'transaccionJson'=>"[]",
        ]);
    }

    public function addItemAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $this->procesarAddAction($data);
            $this->redirect()->toRoute('home');
        }
        return new ViewModel([]);
    }


    // public function editAction() {
    //     $id_transaccion= $this->params()->fromRoute('id');
    //     $pedido = $this->pedidoManager->getPedidoFromTransaccionId($id_transaccion);
    //     $items= array();
    //     if (!is_null($pedido)){
    //         $items = $pedido->getTransaccion()->getBienesTransacciones();
    //     }
    //     $items = $this->getItemsArray($items);
    //     if (!isset($_SESSION['TRANSACCIONES']['PEDIDO'])){
    //         $_SESSION['TRANSACCIONES']['PEDIDO']= $items;
    //     }
        
    //     $items = $_SESSION['TRANSACCIONES']['PEDIDO'];
    //     $json = "";
    //     foreach ($items as $array){
    //         $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
    //         $json .= $item->getJson(). ',';
    //     }
    //     $json = substr($json, 0, -1);
    //     $json = '['.$json.']';
    //     $persona = $pedido->getTransaccion()->getPersona();
    //     $tipoPersona = null;
    //     if($persona->getTipo()=="CLIENTE"){
    //         $tipoPersona= $this->clientesManager->getClienteIdPersona($persona->getId());
    //     }
    //     elseif ($persona->getTipo()=="PROVEEDOR"){
    //         $tipoPersona= $this->proveedorManager->getProveedorIdPersona($persona->getId());
    //     }
    //     if ($this->getRequest()->isPost()) {
    //         $data = $this->params()->fromPost();
    //         $data['tipo'] = $this->getTipo();
    //         $data['persona'] = $persona;
    //         $data['items'] = $_SESSION['TRANSACCIONES']['PEDIDO'];
    //         $this->pedidoManager->edit($pedido, $data);
    //         $url = $data['url'];
    //         if($persona->getTipo()=="CLIENTE"){
    //             $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
    //         }
    //         else{
    //             $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
    //         }
        
    //     }
    //     $numTransacciones= $pedido->getTransaccion()->getNumero(); 
    //     $numPedido = $pedido->getNumero();
    //     $formasPago= $this->pedidoManager->getFormasPago();
    //     $this->reiniciarParams();
    //     return new ViewModel([
    //         'items' => $items,
    //         'persona' => $persona,
    //         'tipoPersona'=>$tipoPersona,
    //         'numTransacciones'=>$numTransacciones,
    //         'numPedido'=>$numPedido,
    //         'json' => $json,
    //         'formasPago' => $formasPago,

    //     ]);
    // }


    public function editAction()
    {
        $id_transaccion = $this->params()->fromRoute('id');
        $pedido = $this->pedidoManager->getPedidoFromTransaccionId($id_transaccion);
        $items = array();

        if (!is_null($pedido)) {
            $items = $pedido->getTransaccion()->getBienesTransacciones();
        }
        $items = $this->getItemsArray($items);
        if (!isset($_SESSION['TRANSACCIONES']['PEDIDO'])) {
            $_SESSION['TRANSACCIONES']['PEDIDO'] = $items;
        }

        $items = $_SESSION['TRANSACCIONES']['PEDIDO'];
        $json = "";
        foreach ($items as $array) {
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            $json .= $item->getJson() . ',';
        }
        $json = substr($json, 0, -1);
        $json = '[' . $json . ']';
        $persona = $pedido->getTransaccion()->getPersona();
        $tipoPersona = null;
        if ($persona->getTipo() == "CLIENTE") {
            $tipoPersona = $this->clientesManager->getClienteIdPersona($persona->getId());
        } elseif ($persona->getTipo() == "PROVEEDOR") {
            $tipoPersona = $this->proveedorManager->getProveedorIdPersona($persona->getId());
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $data['persona'] = $persona;
            // $data['items'] = $_SESSION['TRANSACCIONES']['PEDIDO'];
            $this->pedidoManager->edit($pedido, $data);
            $url = $data['url'];
            if ($persona->getTipo() == "CLIENTE") {
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            } else {
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }
        $numTransacciones = $pedido->getTransaccion()->getNumero();
        $numPedido = $pedido->getNumero();
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $transaccionJson = $pedido->getTransaccion()->getJSON();
        $ivasJson = $this->getJsonIvas();
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numPedido' => $numPedido,
            'json' => $json,
            'formasPagoJson' => $formasPagoJson,
            'monedasJson' => $monedasJson,
            'formasEnvioJson'=>"[]",
            'transaccionJson' => $transaccionJson,
            'ivasJson' => $ivasJson,
        ]);
    }
    public function eliminarItemAction()
    {

        $this->layout()->setTemplate('layout/nulo');
        $pos = $this->params()->fromRoute('id');
        $id = $this->params()->fromRoute('id2');
        array_splice($_SESSION['TRANSACCIONES']['PEDIDO'], $pos, 1);

        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }

    public function autocompletarAction()
    {

        // print_r("autocompletarAction");
        die();

        $this->layout()->setTemplate('layout/nulo');
        
        // $json_bienes = "";
        // $view = new ViewModel(['json_bienes' => $json_bienes]);
        $view->setTerminal(true);
        return $view;
    }

    public function getJsonMonedas()
    {
        $monedas = $this->monedaManager->getMonedas();
        $json = $this->getJsonFromObjectList($monedas);
        return $json;
    }

    public function getJsonFormasPago()
    {
        $formasPago = $this->formaPagoManager->getFormasPago();
        $json = $this->getJsonFromObjectList($formasPago);
        return $json;
    }

    public function getJsonIvas(){
        $ivas = $this->ivaManager->getIvas();
        $json = $this->getJsonFromObjectList($ivas);
        return $json;
    }


}