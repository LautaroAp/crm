<?php

namespace Transaccion\Service;

use DBAL\Entity\Transaccion;
use DBAL\Entity\Categoria;
use DBAL\Entity\Persona;
use DBAL\Entity\Ejecutivo;
use DBAL\Entity\BienesTransacciones;
use DBAL\Entity\CuentaCorriente;
use DBAL\Entity\Empresa;
use DBAL\Entity\Bienes;
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
    protected $bienesManager;
    protected $cuentaCorrienteManager;
    protected $comprobanteManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $personaManager, $bienesTransaccionesManager, $ivaManager,
    $formaPagoManager, $formaEnvioManager, $monedaManager, $bienesManager, $cuentaCorrienteManager, $comprobanteManager) {
        $this->entityManager = $entityManager;
        $this->personaManager= $personaManager;
        $this->bienesTransaccionesManager = $bienesTransaccionesManager;
        $this->ivaManager = $ivaManager;
        $this->formaPagoManager = $formaPagoManager;
        $this->formaEnvioManager = $formaEnvioManager;
        $this->monedaManager = $monedaManager;
        $this->bienesManager = $bienesManager;
        $this->cuentaCorrienteManager = $cuentaCorrienteManager;
        $this->comprobanteManager = $comprobanteManager;

 
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
    public function getTransaccionPrevia($tipo, $id){
        $transaccion_especifica = null;
        if ($tipo=="PEDIDO"){
            $transaccion_especifica= $this->entityManager->getRepository(Presupuesto::class)->findOneById($id);
        }
        if ($tipo=="REMITO"){
            $transaccion_especifica= $this->entityManager->getRepository(Remito::class)->findOneById($id);
        }
        if (!is_null($transaccion_especifica)){
            return $transaccion_especifica->getTransaccion();
        }
        return null;
        
    }
    

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Transaccion::class)); // An object repository implements Selectable
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
        if (isset($data['concepto'])){
            $transaccion->setDetalle($data['concepto']);
        }
        $moneda = null;
        if (isset($data['moneda'])) {
            if ($data['moneda'] != '-1') {
                $moneda = $this->monedaManager->getMonedaId($data['moneda']);
            }
            $transaccion->setMoneda($moneda);
        }
        if (isset($data['idComprobante'])){
            $comprobante = $this->comprobanteManager->getComprobante($data['idComprobante']);
            $transaccion->setComprobante($comprobante);
        }
        if (isset($data['tipo_comprobante'])){
            $tipo_comprobante = $this->comprobanteManager->getComprobanteTipo($data['tipo_comprobante']);
            $transaccion->setTipo_Comprobante($tipo_comprobante);
        }
        $transaccion->setEstado("ACTIVO");
        $transaccion->setMonto($data['total_general']);
        if (isset($data['subtotal_general'])){
            $transaccion->setSubtotal($data['subtotal_general']);
        }
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
        if (isset($data['bonificacion_importe'])){
            $transaccion->setBonificacionImporte($data['bonificacion_importe']);

        }
        if (isset($data['recargo_general']) and $data['recargo_general']!=''){
            $transaccion->setRecargoGeneral($data['recargo_general']);
        }
        if (isset($data['recargo_importe'])){
            $transaccion->setRecargoImporte($data['recargo_importe']);
        }
        // if (isset($data['iva_general'])){
        //     $iva = $this->ivaManager->getIvaId($data['iva_general']);
        // }
        if(isset($data['forma_pago'])){
            $formaPago = $this->formaPagoManager->getFormaPagoId($data['forma_pago']);
            $transaccion->setFormaPago($formaPago);
        }
        if(isset($data['forma_envio'])){           
            $formaEnvio = $this->formaEnvioManager->getFormaEnvioId($data['forma_envio']);
            $transaccion->setFormaEnvio($formaEnvio);
        }

        if (isset($data['importe_gravado'])){
            $transaccion->setImporte_gravado($data['importe_gravado']);
        }
        if (isset($data['importe_no_gravado'])){
            $transaccion->setImporte_no_gravado($data['importe_no_gravado']);
        }
        if (isset($data['importe_exento'])){
            $transaccion->setImporte_exento($data['importe_exento']);
        }

        if (isset($data['importe_iva_27'])){
            $transaccion->setImporte_iva_27($data['importe_iva_27']);
        }
        if (isset($data['importe_iva_21'])){
            $transaccion->setImporte_iva_21($data['importe_iva_21']);
        }
        if (isset($data['importe_iva_10'])){
            $transaccion->setImporte_iva_10($data['importe_iva_10']);
        }
        if (isset($data['importe_iva_5'])){
            $transaccion->setImporte_iva_5($data['importe_iva_5']);
        }
        if (isset($data['importe_iva_2'])){
            $transaccion->setImporte_iva_2($data['importe_iva_2']);
        }
        if (isset($data['importe_iva_0'])){
            $transaccion->setImporte_iva_0($data['importe_iva_0']);
        }

        if (isset($data['oficial'])){
            $transaccion->setOficial($data['oficial']);
        }else{
            $transaccion->setOficial(false);
        }
        
        $transaccionPrevia = null;
        if(isset($data['id_transaccion_previa'])){           
            $transaccionPrevia = $this->getTransaccionId($data['id_transaccion_previa']);
            $transaccion->setTransaccionPrevia($transaccionPrevia);
        }
        if (strtoupper($data['tipo']) !="FACTURA"){
            $transaccion->setFacturado(false);
        }
        else{
            if (!is_null($transaccionPrevia)){
                $transaccionPrevia->setFacturado(true);
            }
            $transaccion->setFacturado(true);
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
            $item->setTransaccion($transaccion);
            if (isset($array["Transaccion Previa"])){
                $transaccionPrevia = $this->getTransaccionId($array["Transaccion Previa"]["Id"]);
                $item->setTransaccionPrevia($transaccionPrevia);
            }

            // MODIFICO STOCK EN CASO DE REMITO
            if (strtoupper($transaccion->getNombre())=="REMITO"){
                $bien= $item->getBien();
                $tipoPersona = $transaccion->getPersona()->getTipo();
                if (strtoupper($bien->getTipo())=="PRODUCTO"){
                    if (strtoupper($tipoPersona)=="CLIENTE"){
                        $stock = $bien->getStock();
                        $stock = $stock - $item->getCantidad();
                        $bien->setStock($stock);
                    }
                    else if (strtoupper($tipoPersona)=="PROVEEDOR"){
                        $stock = $bien->getStock();
                        $stock = $stock + $item->getCantidad();
                        $bien->setStock($stock);
                    }
                }
            }

            // MODIFICO STOCK EN CASO DE FACTURA
            if (strtoupper($transaccion->getNombre()) == "FACTURA"){
                $transaccion_facturar = null;
                if(isset($array["Numero Transaccion"])){
                    $transaccion_facturar = $this->entityManager->getRepository(Transaccion::class)
                                            ->findOneBy(['numero_transaccion' => $array["Numero Transaccion"]]);
                }

                $transaccion_tipo = null;
                if($transaccion_facturar){
                    $transaccion_tipo = $transaccion_facturar->getNombre();
                }

                // print_r(strtoupper($transaccion_tipo)); die();
                if (strtoupper($transaccion_tipo) != "REMITO"){
                    $bien= $item->getBien();
                    $tipoPersona = $transaccion->getPersona()->getTipo();
                    if (strtoupper($bien->getTipo())=="PRODUCTO"){
                        if (strtoupper($tipoPersona)=="CLIENTE"){
                            $stock = $bien->getStock();
                            $stock = $stock - $item->getCantidad();
                            $bien->setStock($stock);
                        }
                        else if (strtoupper($tipoPersona)=="PROVEEDOR"){
                            $stock = $bien->getStock();
                            $stock = $stock + $item->getCantidad();
                            $bien->setStock($stock);
                        }
                    }
                }
                
            }

            $item= $this->bienesTransaccionesManager->add($item);
        }
    }

    
    public function edit($transaccion, $data) {
        $transaccion=$this->setData($transaccion, $data);
        $items = json_decode($data['jsonitems'], true);
        $this->setItems($transaccion,$items);
        $this->entityManager->flush();
        return $transaccion;
    }

    public function add($data) {
        $transaccion = new Transaccion();
        $transaccion=$this->setData($transaccion, $data);
        $this->entityManager->persist($transaccion); 
        $items = json_decode($data['jsonitems'], true);
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

    public function eliminarFormasEnvio($id) {
        $entityManager = $this->entityManager;
        $transacciones = $this->entityManager->getRepository(Transaccion::class)->findBy(['formaEnvio'=>$id]);
        foreach ($transacciones as $transaccion) {
            $transaccion->setFormaEnvio(null);
        }
        $entityManager->flush();
    }


    public function getTransaccionesPersonaTipo($idPersona,$tipoTransaccion){
        if (strtoupper($tipoTransaccion)!="FACTURA"){
            $transacciones = $this->entityManager->getRepository(Transaccion::class)->findBy(['persona'=>$idPersona, 'tipo_transaccion'=>strtoupper($tipoTransaccion), 'facturado'=>false]);
        }
        else{
             $transacciones = $this->entityManager->getRepository(Transaccion::class)->findBy(['persona'=>$idPersona, 'tipo_transaccion'=>strtoupper($tipoTransaccion)]);
        }
       
        // return $transacciones;

        $transaccionesActivas = [];
        foreach ($transacciones as $transaccion){
            if (strtoupper($transaccion->getEstado())!="ANULADO"){
                array_push($transaccionesActivas, $transaccion);
            }
        }
        return $transaccionesActivas;
    }

    // public function setNumeroCuentaCorriente($transaccion, $numero){
    //     $registro = $this->cuentaCorrienteManager->getRegistroTransaccion($transaccion);
    //     $registro->setNroTipoTransaccion($numero);
    //     $this->entityManager->flush();
    // }
    
}
