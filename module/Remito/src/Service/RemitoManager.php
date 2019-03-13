<?php

namespace Remito\Service;

use DBAL\Entity\Remito;
use DBAL\Entity\Moneda;
use DBAL\Entity\Transaccion;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los remitos 
 * 
 */
class RemitoManager {

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

    public function getRemitos() {
        $remitos = $this->entityManager->getRepository(Remito::class)->findAll();
        return $remitos;
    }

    public function getRemitoId($id) {
        return $this->entityManager->getRepository(Remito::class)
                        ->find($id);
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Remito::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    /**
     * This method adds a new remito.
     */
    public function addRemito($data) {
        $remito = new Remito();
        $remito=$this->setData($remito, $data);
        $this->entityManager->persist($remito);
        $this->entityManager->flush();
        return $remito;
    }

    private function setData($remito, $data){
        $data['tipo']=$this->tipo;
        $bien = $this->transaccionManager->addBien($data);
        $remito->setBien($bien);
        return $remito;
    }

    /**
     * This method updates data of an existing remito.
     */
    public function updateRemito($remito, $data) {
        $transaccion = $remito->getBien();
        $data['tipo']=$this->tipo;
        $this->transaccionManager->updateBien($transaccion, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removeRemito($remito) {
        $transaccion = $remito->getBien();
        $this->entityManager->remove($remito);
        $this->entityManager->flush();
    }

}
