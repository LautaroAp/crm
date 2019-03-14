<?php

namespace Transaccion\Service;

use DBAL\Entity\Transaccion;
use DBAL\Entity\Categoria;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los servicios 
 * 
 */
class TransaccionManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    protected $personaManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $personaManager) {
        $this->entityManager = $entityManager;
        $this->proveedorManager= $personaManager;
    }

    public function getTransacciones() {
        $transacciones = $this->entityManager->getRepository(Transaccion::class)->findAll();
        return $transacciones;
    }

    public function getTransaccionId($id) {
        return $this->entityManager->getRepository(Transaccion::class)
                        ->find($id);
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($sthis->entityManager->getRepository(Transaccion::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function getTransaccionTipo($tipo){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('T')
                     ->from(Transaccion::class, 'T')
                     ->where("T.tipo LIKE ?$t")->setParameter("$t", $tipo);
        return $queryBuilder->getQuery();
    }

    
    /**
     * This method adds a new servicio.
     */
    public function addTransaccion($data) {
        $transaccion = new Transaccion();
        $transaccion=$this->setData($transaccion, $data);
        $this->entityManager->persist($transaccion);
        $this->entityManager->flush();
        return $transaccion;
    }

    private function setData($transaccion, $data){
        $transaccion->setNumero($data['numero']);
        $transaccion->setFecha_transaccion($data['fecha_transaccion']);
        if($data['persona'] == "-1"){
            $transaccion->setPersona(null);
        } else {            
            $p=$this->personaManager->getPersona($data['persona']);
            $transaccion->setPersona($p);
        }
        $transaccion->setTipo($data['tipo']);
        $transaccion->setFecha_vencimiento($data['fecha_vencimiento']);
        $transaccion->setBienes_transacciones($data['transacciones']);
        //MONEDA
        return $transaccion;
    }

    /**
     * This method updates data of an existing servicio.
     */
    public function update($transaccion, $data) {
        $transaccion=$this->setData($transaccion, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function remove($transaccion) {
        $this->entityManager->remove($transaccion);
        $this->entityManager->flush();
    }

}
