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
    private $tipo;

    public function __construct($pedidoManager, $monedaManager, $personaManager) {
        parent::__construct($pedidoManager, $personaManager);

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
        // $json = "";
        // foreach ($items as $item){
        //     $json .= $item->getJson(). ',';
        // }
        // $json = substr($json, 0, -1);
        // $json = '['.$json.']';
        // if ($json!="[]"){
        //     print_r($json);
        //     die();  
        // }

        $id_persona = $this->params()->fromRoute('id');
        $persona = $this->personaManager->getPersona($id_persona);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $this->procesarAddAction($data);
            $this->redirect()->toRoute('home');
        }
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            // 'json' => $json,
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


}
