<?php

/**
 * Clase actualmente sin uso
 */

namespace Pedido\Controller;

use Transaccion\Controller\TransaccionController;
use Pedido\Service\PedidoManager;

use Zend\View\Model\ViewModel;
use DBAL\Entity\BienesTransacciones;

class PedidoController extends TransaccionController{

    /**
     * Pedido manager.
     * @var User\Service\PedidoManager 
     */
    protected $pedidoManager;
    private $monedaManager;
    private $clientesManager;
    private $proveedorManager;
    private $tipo;
    private $bienesTransaccionesManager;
    private $items;

    public function __construct($pedidoManager, $monedaManager, $personaManager, $clientesManager, $proveedorManager,
    $bienesTransaccionesManager) {
        parent::__construct($pedidoManager, $personaManager);
        $this->clientesManager=$clientesManager;
        $this->proveedorManager= $proveedorManager;
        $this->pedidoManager = $pedidoManager;
        $this->monedaManager= $monedaManager;
        $this->bienesTransaccionesManager= $bienesTransaccionesManager;
    }

    public function indexAction() {

    }

   
    private function getTipo(){
        return "pedido";
    }

    public function addAction() {
        $items = array();
        if (isset($_SESSION['TRANSACCIONES']['PEDIDO'])){
            $items = $_SESSION['TRANSACCIONES']['PEDIDO'];
        }
        $json = "";
        foreach ($items as $array){
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            $json .= $item->getJson(). ',';
        }
        $json = substr($json, 0, -1);
        $json = '['.$json.']';
        $id_persona = $this->params()->fromRoute('id');
        $persona = $this->personaManager->getPersona($id_persona);
        $tipoPersona = null;

        if($persona->getTipo()=="CLIENTE"){
            $tipoPersona= $this->clientesManager->getClienteIdPersona($id_persona);
        }
        elseif ($persona->getTipo()=="PROVEEDOR"){
            $tipoPersona= $this->proveedorManager->getProveedorIdPersona($id_persona);
        }
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $data['persona'] = $persona;
            $this->pedidoManager->addPedido($data, $items);
            if($persona->getTipo()=="CLIENTE"){
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
            else{
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        
        }
        $numTransacciones= $this->pedidoManager->getTotalTransacciones()+1;
        $numPedido = $this->pedidoManager->getTotalPedidos()+1;
        $formasPago = $this->pedidoManager->getFormasPago();
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona'=>$tipoPersona,
            'numTransacciones'=>$numTransacciones,
            'numPedido'=>$numPedido,
            'json' => $json,
            'formasPago' => $formasPago,
        ]);
    }

    public function addItemAction() {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $this->procesarAddAction($data);
            $this->redirect()->toRoute('home');
        }
        return new ViewModel([
        ]);
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


    public function editAction() {
        $id_transaccion= $this->params()->fromRoute('id');
        $pedido = $this->pedidoManager->getPedidoFromTransaccionId($id_transaccion);
        $items= array();
        if (!is_null($pedido)){
            $items = $pedido->getTransaccion()->getBienesTransacciones();
        }
        $items = $this->getItemsArray($items);
        $json ="";
        foreach ($items as $array){
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            $json .= $item->getJson(). ',';
        }
        $json = substr($json, 0, -1);
        $json = '['.$json.']';
        $persona = $pedido->getTransaccion()->getPersona();
        $tipoPersona = null;
        if($persona->getTipo()=="CLIENTE"){
            $tipoPersona= $this->clientesManager->getClienteIdPersona($persona->getId());
        }
        elseif ($persona->getTipo()=="PROVEEDOR"){
            $tipoPersona= $this->proveedorManager->getProveedorIdPersona($persona->getId());
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $data['persona'] = $persona;
            // $data['items'] = $_SESSION['TRANSACCIONES']['PEDIDO'];
            $this->pedidoManager->edit($pedido, $data);
            $url = $data['url'];
            if($persona->getTipo()=="CLIENTE"){
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
            else{
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }
        $numTransacciones= $pedido->getTransaccion()->getNumero(); 
        $numPedido = $pedido->getNumero();
        $formasPago= $this->pedidoManager->getFormasPago();
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona'=>$tipoPersona,
            'numTransacciones'=>$numTransacciones,
            'numPedido'=>$numPedido,
            'json' => $json,
            'formasPago' => $formasPago,

        ]);
    }
    public function eliminarItemAction(){

        $this->layout()->setTemplate('layout/nulo');
        $pos = $this->params()->fromRoute('id');
        $id = $this->params()->fromRoute('id2');
        array_splice($_SESSION['TRANSACCIONES']['PEDIDO'], $pos,1);

        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }


}
