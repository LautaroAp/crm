<?php

namespace Pedido\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\Pedido;

use DBAL\Entity\Transaccion;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Transaccion\Service\TransaccionManager;
/**
 * Esta clase se encarga de obtener y modificar los datos de los pedidos 
 * 
 */
class PedidoManager extends TransaccionManager{

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    protected $monedaManager;

    private $tipo;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $monedaManager) {
        $this->entityManager = $entityManager;
        $this->monedaManager = $monedaManager;
        $this->tipo = "PEDIDO";
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
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Pedido::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    /**
     * This method adds a new pedido.
     */
    public function addPedido($data) {
        //llamo a add de la transaccion, retorna una transaccion que se le setea al pedido
        $transaccion = parent::add($data);
        $pedido = new Pedido();
        $pedido=$this->setData($pedido, $data, $transaccion);
        $this->entityManager->persist($pedido);
        $this->entityManager->flush();
        return $pedido;
    }

    private function setData($pedido, $data, $transaccion){
        $pedido->setTransaccion($transaccion);
        $pedido->setNumero($data['numero_pedido']);
        $moneda=null;
        if ($data['moneda']!= '-1'){
            $moneda = $this->monedaManager->getMoneda($data['moneda']);
        }
        $pedido->setMoneda($moneda);
        return $pedido;
    }

    /**
     * This method updates data of an existing pedido.
     */
    public function edit($pedido, $data) {
        $transaccion = parent::edit($pedido->getTransaccion(), $data);
        $pedido = $this->setData($pedido, $data, $transaccion);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function remove($pedido) {
        parent::remove($pedido->getTransaccion());
        $this->entityManager->remove($pedido);
        $this->entityManager->flush();
    }

}
