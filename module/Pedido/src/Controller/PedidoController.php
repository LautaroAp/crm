<?php

/**
 * Clase actualmente sin uso
 */

namespace Pedido\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class PedidoController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Pedido manager.
     * @var User\Service\PedidoManager 
     */
    protected $pedidoManager;
    private $transaccionManager;
    private $monedaManager;
    private $tipo;

    public function __construct($entityManager, $pedidoManager, $monedaManager,$transaccionManager) {
        $this->entityManager = $entityManager;
        $this->pedidoManager = $pedidoManager;
        $this->monedaManager= $monedaManager;
        $this->transaccionManager = $transaccionManager;
        $this->tipo = "pedido";
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $paginator = $this->pedidoManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage($this->getElemsPag());
        return new ViewModel([
            'pedidos' => $paginator,
            'volver' => null,
        ]);        
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->pedidoManager->addPedido($data);
            $this->redirect()->toRoute('home');
        }
        $tipo= $this->tipo;
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'tipo'=>$tipo,
            'volver' => $null,
        ]);

    }

    public function editAction() {
        return $this->procesarEditAction();
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id', -1);
        $pedido = $this->pedidoManager->getPedidoId($id);
        $tipo = $this->params()->fromRoute('tipo');
        $transacciones = $this->transaccionesManager->getIvas();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->pedidoManager->updatePedido($pedido, $data);
            return $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'pedido' => $pedido,
            'categorias'=>$categorias,
            'transacciones'=>$transacciones,
            'proveedores'=>$proveedores,
            'tipo'=>"pedido",
            'volver' => $volver,
        ]);
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $pedido = $this->pedidoManager->getPedidoId($id);
        if ($pedido == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            $this->pedidoManager->removePedido($pedido);
            return $this->redirect()->toRoute('home');
        }
    }

    public function viewAction() {
        return new ViewModel();
    }

    public function backupAction(){
        $this->layout()->setTemplate('layout/nulo');
        $resultado = $this->pedidoManager->getPedidos();
        return new ViewModel([
            'resultado' => $resultado
        ]);
    }


}
