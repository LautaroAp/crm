<?php

namespace Presupuesto\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\Presupuesto;

use DBAL\Entity\Transaccion;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Transaccion\Service\TransaccionManager;
/**
 * Esta clase se encarga de obtener y modificar los datos de los pedidos 
 * 
 */
class PresupuestoManager extends TransaccionManager{

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
        $this->tipo = "PRESUPUESTO";
    }

    public function getPresupuestos() {
        $pedidos = $this->entityManager->getRepository(Presupuesto::class)->findAll();
        return $pedidos;
    }

    public function getPresupuestoId($id) {
        return $this->entityManager->getRepository(Presupuesto::class)
                        ->find($id);
    }


    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Presupuesto::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    /**
     * This method adds a new presupuesto.
     */
    public function addPresupuesto($data) {
        //llamo a add de la transaccion, retorna una transaccion que se le setea al presupuesto
        $transaccion = parent::add($data);
        $presupuesto = new Presupuesto();
        $presupuesto=$this->setData($presupuesto, $data, $transaccion);
        $this->entityManager->persist($presupuesto);
        $this->entityManager->flush();
        return $presupuesto;
    }

    private function setData($presupuesto, $data, $transaccion){
        $presupuesto->setTransaccion($transaccion);
        $presupuesto->setNumero($data['numero_pedido']);
        $moneda=null;
        if ($data['moneda']!= '-1'){
            $moneda = $this->monedaManager->getMoneda($data['moneda']);
        }
        $presupuesto->setMoneda($moneda);
        return $presupuesto;
    }

    /**
     * This method updates data of an existing presupuesto.
     */
    public function edit($presupuesto, $data) {
        $transaccion = parent::edit($presupuesto->getTransaccion(), $data);
        $presupuesto = $this->setData($presupuesto, $data, $transaccion);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function remove($presupuesto) {
        parent::remove($presupuesto->getTransaccion());
        $this->entityManager->remove($presupuesto);
        $this->entityManager->flush();
    }

}
