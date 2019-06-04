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

    public function getTransaccionesTipos($persona, $tipos){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('T')
                     ->from(CuentaCorriente::class, 'T');
        $nombreCampo="persona";
        $queryBuilder->where("T.persona = :p")->setParameter('p',$persona);
        $queryBuilder->andWhere('T.tipoActividad IN (:tipos)')->setParameter('tipos', $tipos);
        $queryBuilder->andWhere("T.facturado = :f")->setParameter('f',false);
        $queryBuilder->orderBy('T.fecha', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function getVentas($idPersona = null){
        if(isset($idPersona)){
            $persona = $this->personaManager->getPersona($idPersona);
            // if(strtoupper($persona->getTipo())=="CLIENTE"){
                $tipos = ["remito", "factura", "nota de credito", "nota de debito"];
            // }else{
            //     $tipos = ["cobro"];
            // }
            $transacciones = $this->getTransaccionesTipos($persona,$tipos);
            return $transacciones;    
        } else {
            return $this->entityManager
                        ->getRepository(CuentaCorriente::class)
                        // ->findAll();
                        ->findBy(['tipoActividad' => ['remito']]);
        }
        
    }
    
    public function getCobros($idPersona = null){
        if(isset($idPersona)){
            $persona = $this->personaManager->getPersona($idPersona);
            // if(strtoupper($persona->getTipo())=="CLIENTE"){
                $tipos = ["cobro"];
            // }else{
            //     $tipos =["remito", "factura"]; 
            // }

            $transacciones = $this->getTransaccionesTipos($persona,$tipos);
            return $transacciones;    
        } else {
            return $this->entityManager
                        ->getRepository(CuentaCorriente::class)
                        ->findBy(['tipoActividad' => ['cobro']]);
        }

        
    }

    /**
     * This method adds a new register.
     */
    public function add($transaccion) {
        $tipoTransaccion = strtoupper($transaccion->getTipo());
        if ($tipoTransaccion=="REMITO"||$tipoTransaccion=="FACTURA" ||$tipoTransaccion=="RECIBO" || $tipoTransaccion=="COBRO"
        || $tipoTransaccion=="NOTA DE CREDITO" || $tipoTransaccion=="NOTA DE DEBITO"){
            $cuentaCorriente = new CuentaCorriente();
            $cuentaCorriente=$this->setData($cuentaCorriente, $transaccion);
            $this->entityManager->persist($cuentaCorriente);
            $this->entityManager->flush();
            return $cuentaCorriente;
        }
        return null;
    }

    private function setData($cuentaCorriente, $transaccion){
        $cuentaCorriente->setPersona($transaccion->getPersona());
        $cuentaCorriente->setTransaccion($transaccion);
        if (strtoupper($transaccion->getTipo())=="NOTA DE CREDITO"){
            $cuentaCorriente->setMonto($transaccion->getMonto()*-1);
        }else{
            $cuentaCorriente->setMonto($transaccion->getMonto());
        }
        $cuentaCorriente->setFecha($transaccion->getFecha_transaccion());
        $cuentaCorriente->setNroTipoTransaccion($transaccion->getNumeroTransaccionTipo());
        $cuentaCorriente->setTipoActividad($transaccion->getTipo());

        //SE GUARDAN LAS FACTURAS COMO NO FACTURADOS PARA HACER MAS FACIL LA CONSULTA SQL
        //PARA OBTENER LAS VENTAS 
        if (strtoupper($transaccion->getTipo())=="FACTURA"){
            $cuentaCorriente->setFacturado(false);
        }else{
            $cuentaCorriente->setFacturado($transaccion->getFacturado());
        }
        return $cuentaCorriente;
    }

    /**
     * This method updates data of an existing servicio.
     */
    public function edit($transaccion) {
        $cuentaCorriente = $this->getRegistroTransaccion($transaccion);
        if (!is_null($cuentaCorriente)){
            $cuentaCorriente=$this->setData($cuentaCorriente, $transaccion);
            // Apply changes to database.
            $this->entityManager->flush();
            return true;
        }
    }

    public function getRegistroTransaccion($transaccion){
        $idTransaccion = $transaccion->getId();
        return $this->entityManager->getRepository(CuentaCorriente::class)->findOneBy(['transaccion'=>$idTransaccion]);
    }

    public function remove($transaccion) {
        $cuentaCorriente = $this->getRegistroTransaccion($transaccion);
        $this->entityManager->remove($cuentaCorriente);
        $this->entityManager->flush();
    }

    public function setFacturado($transaccionPrevia){
        if($transaccionPrevia){
            $idTP = $transaccionPrevia->getId();
            $registro =$this->entityManager->getRepository(CuentaCorriente::class)->findOneBy(['transaccion'=>$idTP]);
            if (!is_null($registro)){   
                $registro->setFacturado(true);
            }
        } 
    }

}
