<?php

/**
 * Clase actualmente sin uso
 */

namespace Pedido\Controller;

use Transaccion\Controller\TransaccionController;
use Pedido\Service\PedidoManager;

use Zend\View\Model\ViewModel;

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

    public function __construct($pedidoManager, $monedaManager, $personaManager, $clientesManager, $proveedorManager) {
        parent::__construct($pedidoManager, $personaManager);
        $this->clientesManager=$clientesManager;
        $this->proveedorManager= $proveedorManager;
        $this->pedidoManager = $pedidoManager;
        $this->monedaManager= $monedaManager;
        

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
        foreach ($items as $item){
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
            $this->redirect()->toRoute('home');
        }
        $numTransacciones= $this->pedidoManager->getTotalTransacciones()+1;
        $numPedido = $this->pedidoManager->getTotalPedidos()+1;
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona'=>$tipoPersona,
            'numTransacciones'=>$numTransacciones,
            'numPedido'=>$numPedido,
            'json' => $json,
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

    public function editAction() {
        $id = $this->params()->fromRoute('id', -1);
        $pedido = $this->pedidoManager->getPedidoId($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $this->procesarEditAction($pedido, $data);
            return $this->redirect()->toRoute('home');
        }       
        return new ViewModel([
            'pedido' => $pedido,
            'transaccion'=>$pedido->getTransaccion(),
            'persona'=>$transaccion->getPersona(),
            'tipo'=>$this->getTipo(),
        ]);    
    }

    public function eliminarItemAction(){
        $this->layout()->setTemplate('layout/nulo');
        $pos = $this->params()->fromRoute('id');
        $id = $this->params()->fromRoute('id2');
        array_splice($_SESSION['TRANSACCIONES']['PEDIDO'], $pos,1);
        // return $this->redirect()->toRoute('pedido/add/'.$id);
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }


}
