<?php

/**
 * Clase actualmente sin uso
 */

namespace Presupuesto\Controller;

use Transaccion\Controller\TransaccionController;
use Presupuesto\Service\PresupuestoManager;

use Zend\View\Model\ViewModel;

class PresupuestoController extends TransaccionController{

    /**
     * Presupuesto manager.
     * @var User\Service\PresupuestoManager 
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
        return "presupuesto";
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

    public function editAction() {
        $id = $this->params()->fromRoute('id', -1);
        $presupuesto = $this->pedidoManager->getPresupuestoId($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $this->procesarEditAction($presupuesto, $data);
            return $this->redirect()->toRoute('home');
        }       
        return new ViewModel([
            'presupuesto' => $presupuesto,
            'transaccion'=>$presupuesto->getTransaccion(),
            'persona'=>$transaccion->getPersona(),
            'tipo'=>$this->getTipo(),
        ]);    
    }


}
