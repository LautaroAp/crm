<?php

namespace Transaccion\Service;

use DBAL\Entity\Transaccion;
use DBAL\Entity\Categoria;
use DBAL\Entity\Persona;
use DBAL\Entity\BienesTransacciones;
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
    protected $bienesTransaccionesManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $personaManager, $bienesTransaccionesManager) {
        $this->entityManager = $entityManager;
        $this->personaManager= $personaManager;
        $this->bienesTransaccionesManager = $bienesTransaccionesManager;
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
    public function add($data, $items) {
        $transaccion = new Transaccion();
        $transaccion=$this->setData($transaccion, $data);
        $this->entityManager->persist($transaccion);    
        $this->setItems($transaccion, $items);
        $this->entityManager->flush();
        return $transaccion;
    }

    private function getUltimoId(){
        $highest_id = $this->entityManager->createQueryBuilder()
        ->select('MAX(T.id)')
        ->from(Transaccion::Class, 'T')
        ->getQuery()
        ->getSingleScalarResult();
    }

    private function setData($transaccion, $data){

        if (isset($data['numero_transaccion'])){
            $transaccion->setNumero($data['numero_transaccion']);
        }
        else{
            $transaccion->setNumero($this->getUltimoId()+1);
        }
        if (isset($data['fecha_transaccion'])){
            $fecha_transaccion = \DateTime::createFromFormat('d/m/Y', $data['fecha_transaccion']); 
            $transaccion->setFecha_transaccion($fecha_transaccion);
        }
        $transaccion->setPersona($data['persona']);
        $transaccion->setTipo($data['tipo']);
        if (isset($data['fecha_vencimiento'])){
            $fecha_vencimiento = \DateTime::createFromFormat('d/m/Y', $data['fecha_evento']);
            $transaccion->setFecha_vencimiento($fecha_vencimiento);
        }
        return $transaccion;
    }

    private function setItems($transaccion, $items){
        foreach($items as $item ){
            $item->setTransaccion($transaccion);
            $item= $this->bienesTransaccionesManager->add($item);
            $transaccion->addBien_transaccion($item);
        }
    }
    /**
     * This method updates data of an existing servicio.
     */
    public function edit($transaccion, $data) {
        //le llega un objeto transaccion (padre)
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
