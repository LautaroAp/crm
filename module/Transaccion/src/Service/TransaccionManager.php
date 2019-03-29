<?php

namespace Transaccion\Service;

use DBAL\Entity\Transaccion;
use DBAL\Entity\Categoria;
use DBAL\Entity\Persona;
use DBAL\Entity\Ejecutivo;
use DBAL\Entity\BienesTransacciones;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use DateInterval;

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
    protected $ivaManager;
    protected $formaPagoManager;    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $personaManager, $bienesTransaccionesManager, $ivaManager,
    $formaPagoManager) {
        $this->entityManager = $entityManager;
        $this->personaManager= $personaManager;
        $this->bienesTransaccionesManager = $bienesTransaccionesManager;
        $this->ivaManager = $ivaManager;
        $this->formaPagoManager = $formaPagoManager;
    }

    public function getTransacciones() {
        $transacciones = $this->entityManager->getRepository(Transaccion::class)->findAll();
        return $transacciones;
    }

    public function getTransaccionId($id) {
        return $this->entityManager->getRepository(Transaccion::class)
                        ->find($id);
    }

    public function getTransaccionesPersona($id_persona){
        return $this->entityManager->getRepository(Transaccion::class)->findBy(['persona'=>$id_persona]);
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

    public function getTotalTransacciones(){
        $highest_id = $this->entityManager->createQueryBuilder()
        ->select('COUNT(T.id)')
        ->from(Transaccion::Class, 'T')
        ->getQuery()
        ->getSingleScalarResult();
    }

    private function setData($transaccion, $data){
        if (isset($data['numero_transaccion'])){
            $transaccion->setNumero($data['numero_transaccion']);
        }
        else{
            $transaccion->setNumero($this->getTotalTransacciones()()+1);
        }
        if (isset($data['nombre'])){
            $transaccion->setNombre($data['nombre']);
        }
        else{
            $transaccion->setNombre(ucfirst($data['tipo']));
        }
        $transaccion->setEstado("S"); //S ES ACTIVO COMO EN CLIENTES
        $transaccion->setMonto(substr($data['total_general'], 2));
        $ejecutivo = $this->entityManager->getRepository(Ejecutivo::class)
        ->findOneBy(['usuario' => $data['responsable']]);     
        $transaccion->setResponsable($ejecutivo);
        if (isset($data['fecha_transaccion'])){
            $fecha_transaccion = \DateTime::createFromFormat('d-m-Y', $data['fecha_transaccion']); 
            $transaccion->setFecha_transaccion($fecha_transaccion);
        }
        $transaccion->setPersona($data['persona']);
        $transaccion->setTipo($data['tipo']);
        if (isset($data['fecha_vencimiento'])){
            $fecha_vencimiento = \DateTime::createFromFormat('d-m-Y', $data['fecha_evento']);
            $transaccion->setFecha_vencimiento($fecha_vencimiento);
        }
        if (isset($data['bonificacion_general'])){
            $transaccion->setBonificacionGeneral($data['bonificacion_general']);
        }
        if (isset($data['iva_general'])){
            $iva = $this->ivaManager->getIvaId($data['iva_general']);
        }
        if(isset($data['forma_pago'])){
            $formaPago = $this->formaPagoManager->getFormaPagoId($data['forma_pago']);
            $transaccion->setFormaPago($formaPago);
        }
        return $transaccion;
    }

    private function setItems($transaccion, $items){
        //LOS BIENESTRANSACCIONES SE GUARDAN COMO ARREGLO
        foreach($items as $array ){
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            $item->setTransaccion($transaccion);
            // $transaccion->addBienesTransacciones($item);
            $bien= $item->getBien();
            // $bien->addBienesTransacciones($item);
            $item= $this->bienesTransaccionesManager->add($item);
        }
    }
    private function setItemsEdit($transaccion, $items){
        //LOS BIENESTRANSACCIONES SE GUARDAN COMO ARREGLO
        $itemsAnteriores = $transaccion->getBienesTransacciones();
        if (!is_null($itemsAnteriores)){        
            $this->bienesTransaccionesManager->borrarBienesTransacciones($itemsAnteriores);
        }
        foreach($items as $array ){
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            $item->setTransaccion($transaccion);
            // $transaccion->addBienesTransacciones($item);
            $bien= $item->getBien();
            // $bien->addBienesTransacciones($item);
            $item= $this->bienesTransaccionesManager->add($item);
        }
    }

    public function edit($transaccion, $data) {
        $transaccion=$this->setData($transaccion, $data);
        $this->setItemsEdit($transaccion, $data['items']);
        $this->entityManager->flush();
        return $transaccion;
    }

    public function remove($transaccion) {
        $this->entityManager->remove($transaccion);
        $this->entityManager->flush();
    }

    public function getFormasPago(){
        return $this->formaPagoManager->getFormasPago();
    }

}
