<?php

namespace CuentaCorriente\Service;

use DBAL\Entity\CuentaCorriente;
use DBAL\Entity\Categoria;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los servicios 
 * 
 */
class CuentaCorrienteManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $personaManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $personaManager) {
        $this->entityManager = $entityManager;
        $this->personaManager= $personaManager;
    }

    public function getCuentasCorriente() {
        $cuentasCorriente = $this->entityManager->getRepository(CuentaCorriente::class)->findAll();
        return $cuentasCorriente;
    }

    public function getCuentaCorrienteId($id) {
        return $this->entityManager->getRepository(CuentaCorriente::class)
                        ->findOneById($id);
    }

    public function getTransaccionesTipos($idPersona, $tipos){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('T')
                     ->from(CuentaCorriente::class, 'T');
        $queryBuilder->where("T.persona = ?$p")->setParameter("$p", $idPersona);
        $queryBuilder->andWhere('T.tipo_actividad IN (:tipos)')->setParameter('tipos', $tipos);
        $queryBuilder->orderBy('T.fecha', 'DESC');
        return $queryBuilder->getQuery();
    }

    public function getVentas($persona){
        $idPersona = $persona->getId();
        if(strtoupper($persona->getTipo())=="CLIENTE"){
            $tipos = ["remito", "factura"];
        }else{
            $tipos = ["recibo"];
        }
        $transacciones = $this->getTransaccionesTipos($idPersona,$tipos);
        return $transacciones;
    }
    
    public function getCobros($persona){
        $idPersona = $persona->getId();
        if(strtoupper($persona->getTipo())=="CLIENTE"){
            $tipos = ["recibo"];
        }else{
            $tipos =["remito", "factura"]; 
        }
        $transacciones = $this->getTransaccionesTipos($idPersona,$tipos);
        return $transacciones;
    }

    /**
     * This method adds a new register.
     */
    public function add($transaccion) {
        $cuentaCorriente = new CuentaCorriente();
        $cuentaCorriente=$this->setData($cuentaCorriente, $transaccion);
        $this->entityManager->persist($cuentaCorriente);
        $this->entityManager->flush();
        return $cuentaCorriente;
    }

    private function setData($cuentaCorriente, $transaccion){
        $cuentaCorriente->setPersona($transaccion->getPersona());
        $cuentaCorriente->setTransaccion($transaccion);
        $cuentaCorriente->setMonto($transaccion->getMonto());
        $cuentaCorriente->setFecha($transaccion->getFecha());
        $cuentaCorriente->setNroTipoTransaccion($transaccion->getNumeroTransaccionTipo());
        $cuentaCorriente->setTipoActividad($transaccion->getTipo());
        return $cuentaCorriente;
    }

    /**
     * This method updates data of an existing servicio.
     */
    public function update($cuentaCorriente, $transaccion) {
        $cuentaCorriente=$this->setData($cuentaCorriente, $transaccion);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function getRegistroTransaccion($transaccion){
        $idTransaccion = $transaccion->getId();
        return $this->entityManager->getRepository(CuentaCorriente::class)
                        ->findOneBy(['transaccion',$idTransaccion]);
    }

    public function remove($transaccion) {
        $cuentaCorriente = $this->entityManager->getRegistroTransaccion($transaccion);
        $this->entityManager->remove($cuentaCorriente);
        $this->entityManager->flush();
    }

}
