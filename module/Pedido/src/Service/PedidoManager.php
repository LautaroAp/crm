<?php

namespace Pedido\Service;

use DBAL\Entity\Pedido;
use DBAL\Entity\Moneda;
use DBAL\Entity\Transaccion;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los pedidos 
 * 
 */
class PedidoManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected $ivaManager;
    protected $categoriaManager;
    protected $transaccionManager;
    private $tipo;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $ivaManager,$transaccionManager) {
        $this->entityManager = $entityManager;
        $this->ivaManager=$ivaManager;
        $this->categoriaManager=$categoriaManager;
        $this->proveedorManager= $proveedorManager;
        $this->transaccionManager = $transaccionManager;
        $this->tipo = "PRESUPUESTO";
    }

    public function getPedidos() {
        $pedidos = $this->entityManager->getRepository(Pedido::class)->findAll();
        return $pedidos;
    }

    public function getPedidoId($id) {
        return $this->entityManager->getRepository(Pedido::class)
                        ->find($id);
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Pedido::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    /**
     * This method adds a new pedido.
     */
    public function addPedido($data) {
        $pedido = new Pedido();
        $pedido=$this->setData($pedido, $data);
        $this->entityManager->persist($pedido);
        $this->entityManager->flush();
        return $pedido;
    }

    private function setData($pedido, $data){
        $data['tipo']=$this->tipo;
        $transaccion = $this->transaccionManager->addTransaccion($data);
        $pedido->setTransaccion($transaccion);
        return $pedido;
    }

    /**
     * This method updates data of an existing pedido.
     */
    public function updatePedido($pedido, $data) {
        $transaccion = $pedido->getTransaccion();
        $data['tipo']=$this->tipo;
        $this->transaccionManager->updateTransaccion($transaccion, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removePedido($pedido) {
        $transaccion = $pedido->getTransaccion();
        $this->entityManager->remove($pedido);
        $this->entityManager->flush();
    }

}
