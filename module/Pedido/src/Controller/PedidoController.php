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

    public function __construct($pedidoManager, $monedaManager) {
        parent::__construct($pedidoManager);

        $this->pedidoManager = $pedidoManager;
        $this->monedaManager= $monedaManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        // $paginator = $this->pedidoManager->getTabla();
        // $page = 1;
        // if ($this->params()->fromRoute('id')) {
        //     $page = $this->params()->fromRoute('id');
        // }
        // $paginator->setCurrentPageNumber((int) $page)
        //         ->setItemCountPerPage($this->getElemsPag());
        // return new ViewModel([
        //     'pedidos' => $paginator,
        //     'volver' => null,
        // ]);        
    }

    private function getTipo(){
        return "pedido";
    }

    public function addAction() {
        $id = $this->params()->fromRoute('id', -1);
        // $persona= $this->personaManager->getPersona($Id);
        // $cliente = $this->clienteManager->getClienteIdPersona($persona->getId());

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


    public function removeAction() {
        // $view = $this->procesarRemoveAction();
        // return $view;
    }

    public function procesarRemoveAction() {
        // $id = (int) $this->params()->fromRoute('id', -1);
        // $pedido = $this->pedidoManager->getPedidoId($id);
        // if ($pedido == null) {
        //     $this->getResponse()->setStatusCode(404);
        //     return;
        // } else {
        //     $this->pedidoManager->removePedido($pedido);
        //     return $this->redirect()->toRoute('home');
        // }
    }


    public function backupAction(){
        // $this->layout()->setTemplate('layout/nulo');
        // $resultado = $this->pedidoManager->getPedidos();
        // return new ViewModel([
        //     'resultado' => $resultado
        // ]);
    }


}
