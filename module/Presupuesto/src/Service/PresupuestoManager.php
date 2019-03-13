<?php

namespace Presupuesto\Service;

use DBAL\Entity\Presupuesto;
use DBAL\Entity\Moneda;
use DBAL\Entity\Transaccion;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los presupuestos 
 * 
 */
class PresupuestoManager {

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

    public function getPresupuestos() {
        $presupuestos = $this->entityManager->getRepository(Presupuesto::class)->findAll();
        return $presupuestos;
    }

    public function getPresupuestoId($id) {
        return $this->entityManager->getRepository(Presupuesto::class)
                        ->find($id);
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Presupuesto::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    /**
     * This method adds a new presupuesto.
     */
    public function addPresupuesto($data) {
        $presupuesto = new Presupuesto();
        $presupuesto=$this->setData($presupuesto, $data);
        $this->entityManager->persist($presupuesto);
        $this->entityManager->flush();
        return $presupuesto;
    }

    private function setData($presupuesto, $data){
        $data['tipo']=$this->tipo;
        $bien = $this->transaccionManager->addBien($data);
        $presupuesto->setBien($bien);
        return $presupuesto;
    }

    /**
     * This method updates data of an existing presupuesto.
     */
    public function updatePresupuesto($presupuesto, $data) {
        $transaccion = $presupuesto->getBien();
        $data['tipo']=$this->tipo;
        $this->transaccionManager->updateBien($transaccion, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removePresupuesto($presupuesto) {
        $transaccion = $presupuesto->getBien();
        $this->entityManager->remove($presupuesto);
        $this->entityManager->flush();
    }

}
