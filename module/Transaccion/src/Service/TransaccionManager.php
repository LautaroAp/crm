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
    protected $formaEnvioManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $personaManager, $bienesTransaccionesManager, $ivaManager,
    $formaPagoManager, $formaEnvioManager, $monedaManager) {
        $this->entityManager = $entityManager;
        $this->personaManager= $personaManager;
        $this->bienesTransaccionesManager = $bienesTransaccionesManager;
        $this->ivaManager = $ivaManager;
        $this->formaPagoManager = $formaPagoManager;
        $this->formaEnvioManager = $formaEnvioManager;
        $this->monedaManager = $monedaManager;

 
    }

    public function getTransacciones() {
        $transacciones = $this->entityManager->getRepository(Transaccion::class)->findAll();      
        return $transacciones;
    }

    public function getTransaccionId($id) {
        return $this->entityManager->getRepository(Transaccion::class)
                        ->findOneById($id);
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

    public function getTotalTransacciones(){
        $highest_id = $this->entityManager->createQueryBuilder()
        ->select('COUNT(T.id)')
        ->from(Transaccion::Class, 'T')
        ->getQuery()
        ->getSingleScalarResult();
        return $highest_id;
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
        $moneda = null;
        if (isset($data['moneda'])) {
            if ($data['moneda'] != '-1') {
                $moneda = $this->monedaManager->getMonedaId($data['moneda']);
            }
            $transaccion->setMoneda($moneda);
        }
        $transaccion->setEstado("S"); //S ES ACTIVO COMO EN CLIENTES
        $transaccion->setMonto(substr($data['total_general'], 2));
        $ejecutivo = $this->entityManager->getRepository(Ejecutivo::class)
        ->findOneBy(['usuario' => $data['responsable']]);     
        $transaccion->setResponsable($ejecutivo);
        if (isset($data['fecha_transaccion'])){
            $fecha_transaccion = \DateTime::createFromFormat('d/m/Y', $data['fecha_transaccion']); 
            $transaccion->setFecha_transaccion($fecha_transaccion);
        }
        $transaccion->setPersona($data['persona']);
        $transaccion->setTipo($data['tipo']);
        if (isset($data['fecha_vencimiento'])){
            $fecha_vencimiento = \DateTime::createFromFormat('d/m/Y', $data['fecha_vencimiento']);
            $transaccion->setFecha_vencimiento($fecha_vencimiento);
        }
        if (isset($data['bonificacion_general']) and $data['bonificacion_general']!=''){
            $transaccion->setBonificacionGeneral($data['bonificacion_general']);
        }
        $transaccion->setBonificacionImporte(substr($data['bonificacion_importe'],2));
        if (isset($data['recargo_general']) and $data['recargo_general']!=''){
            $transaccion->setRecargoGeneral($data['recargo_general']);
        }
        $transaccion->setRecargoImporte(substr($data['recargo_importe'],2));
        if (isset($data['iva_general'])){
            $iva = $this->ivaManager->getIvaId($data['iva_general']);
        }
        if(isset($data['forma_pago'])){
            $formaPago = $this->formaPagoManager->getFormaPagoId($data['forma_pago']);
            $transaccion->setFormaPago($formaPago);
        }

        if(isset($data['forma_envio'])){           
            $formaEnvio = $this->formaEnvioManager->getFormaEnvioId($data['forma_envio']);
            $transaccion->setFormaEnvio($formaEnvio);
        }
        return $transaccion;
    }

    private function setItems($transaccion, $items){
        //LOS BIENESTRANSACCIONES SE GUARDAN COMO ARREGLO
        $itemsAnteriores = $transaccion->getBienesTransacciones();
        if (!is_null($itemsAnteriores)){        
            $this->bienesTransaccionesManager->borrarBienesTransacciones($itemsAnteriores);
        }
        foreach($items as $array ){
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            // $item = $this->bienesTransaccionesManager->getBienTransaccionFromJson($json);

            $item->setTransaccion($transaccion);
            // $transaccion->addBienesTransacciones($item);
            $bien= $item->getBien();
            // $bien->addBienesTransacciones($item);
            $item= $this->bienesTransaccionesManager->add($item);
        }
    }

    public function edit($transaccion, $data) {
        $items = json_decode($data['jsonitems'], true);
        $transaccion=$this->setData($transaccion, $data);
        // $this->setItems($transaccion, $data['items']);
        $this->setItems($transaccion,$items);
        $this->entityManager->flush();
        return $transaccion;
    }

    public function add($data) {
        $transaccion = new Transaccion();
        $items = json_decode($data['jsonitems'], true);
        $transaccion=$this->setData($transaccion, $data);
        // $this->setItems($transaccion, $data['items']);
        $this->entityManager->persist($transaccion);    
        $this->setItems($transaccion,$items);
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

    public function eliminarFormasPago($id) {
        $entityManager = $this->entityManager;
        $transacciones = $this->entityManager->getRepository(Transaccion::class)->findBy(['formaPago'=>$id]);
        foreach ($transacciones as $transaccion) {
            $transaccion->setFormaPago(null);
        }
        $entityManager->flush();
    }

    public function getTransaccionesPersonaTipo($idPersona,$tipoTransaccion){
        $transacciones = $this->entityManager->getRepository(Transaccion::class)->findBy(['persona'=>$idPersona, 'tipo_trasaccion'=>strtoupper($tipoTransaccion)]);
        return $transacciones;
    }
}
