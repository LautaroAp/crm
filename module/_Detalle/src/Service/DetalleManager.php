<?php

namespace Detalle\Service;

use DBAL\Entity\Detalle;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaProductos
 * and changing categoriaProducto password.
 */
class DetalleManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * PHP template renderer.
     * @var type 
     */
    private $viewRenderer;

    /**
     * Application config.
     * @var type 
     */
    private $config;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $viewRenderer, $config) {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    public function getDetalles() {
        $detalles = $this->entityManager->getRepository(Detalle::class)->findAll();
        return $detalles;
    }

    public function getDetalle($id) {
        return $this->entityManager->getRepository(Detalle::class)
                        ->find($id);
    }

    public function getTabla($tipo) {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Detalle::class)->
        findBy(['tipo'=>$tipo])); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

        
    /**
     * This method adds a new servicio.
     */
    public function addDetalle($data) {
        $detalle = new Detalle();
        $detalle=$this->setData($detalle, $data);
        $this->entityManager->persist($detalle);
        $this->entityManager->flush();
        return $detalle;
    }

    public function add($detalle){
        $this->entityManager->merge($detalle);
        $this->entityManager->flush();
        return $detalle;
    }
    
    private function setData($detalle, $data){
        $transaccion = $this->transaccionManager->getTransaccion($data['idTransaccion']);
        $detalle->seTransaccion($transaccion);
        $detalle->setDetalle($data['detalle']);
        $detalle->setNroTipoTransaccion($data['nroTipoTransaccion']);
        $detalle->Monto($data['monto']);
        return $detalle;
    }

    /**
     * This method updates data of an existing servicio.
     */
    public function updateDetalle($detalle, $data) {
        $detalle=$this->setData($detalle, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removeDetalle($detalle) {
        $this->entityManager->remove($detalle);
        $this->entityManager->flush();
    }

    public function borrarDetalles($detalles){
        foreach ($detalles as $detalle){
            $this->removeDetalle($detalle);
        }
    }


}
