<?php

namespace Factura\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\Factura;
use DBAL\Entity\Presupuesto;
use DBAL\Entity\Persona;
use DBAL\Entity\Cliente;
use DBAL\Entity\Empresa;
use DBAL\Entity\BienesTransacciones;
use DBAL\Entity\Transaccion;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Transaccion\Service\TransaccionManager;
use DateInterval;

/**
 * Esta clase se encarga de obtener y modificar los datos de los facturas 
 * 
 */
class FacturaManager extends TransaccionManager
{

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    protected $monedaManager;
    protected $personaManager;
    protected $bienesTransaccionManager;
    protected $ivaManager;
    private $tipo;
    private $tipoComprobanteManager;
    /**
     * Constructs the service.
     */
    public function __construct(
        $entityManager,
        $monedaManager,
        $personaManager,
        $bienesTransaccionManager,
        $ivaManager,
        $formaPagoManager,
        $formaEnvioManager, 
        $bienesManager,
        $cuentaCorrienteManager,
        $tipoComprobanteManager,
        $comprobanteManager
    ) {
        parent::__construct($entityManager, $personaManager, $bienesTransaccionManager, $ivaManager, $formaPagoManager, $formaEnvioManager, $monedaManager, $bienesManager, $cuentaCorrienteManager, $comprobanteManager);
        $this->entityManager = $entityManager;
        $this->tipo = "Factura";
        $this->tipoComprobanteManager = $tipoComprobanteManager;
    }

    public function getFacturas()
    {
        $facturas = $this->entityManager->getRepository(Factura::class)->findAll();
        return $facturas;
    }

    public function getFacturaId($id)
    {
        return $this->entityManager->getRepository(Factura::class)
            ->find($id);
    }

    public function getFacturaFromTransaccionId($id)
    {
        return $this->entityManager->getRepository(Factura::class)
            ->findOneBy(['transaccion' => $id]);
    }

    public function getTabla()
    {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Factura::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }


    private function setData($factura, $data, $transaccion)
    {
        $factura->setTransaccion($transaccion);
        if (isset($data['numero_factura'])) {
            $factura->setNumero($data['numero_factura']);
            $transaccion->setNumeroTransaccionTipo($data['numero_factura']);
        }
        if (isset($data['tipo_comprobante'])){
            if ($data['tipo_comprobante']!=-1){
                $idFactura = $data['tipo_comprobante'];
                $tipoComprobante = $this->tipoComprobanteManager->getTipoComprobante($idFactura);
                $factura->setTipo_comprobante($tipoComprobante);
            }
        }
        return $factura;
    }

    /**
     * This method updates data of an existing factura.
     */
    public function edit($factura, $data)
    {
        $transaccion = parent::edit($factura->getTransaccion(), $data);
        $factura = $this->setData($factura, $data, $transaccion);
        $this->actualizaFechas($data);

        // Apply changes to database.
        $this->entityManager->flush();
        $this->cuentaCorrienteManager->edit($transaccion);

        return true;
    }

    public function add($data)
    {
        $transaccion = parent::add($data);
        $factura = new Factura();
        $factura = $this->setData($factura, $data, $transaccion);
        $this->actualizaFechas($data);
        $this->cuentaCorrienteManager->add($transaccion);
        $transaccionPrevia = $transaccion->getTransaccionPrevia();
        $this->cuentaCorrienteManager->setFacturado($transaccionPrevia);
        // Apply changes to database.
        $this->entityManager->persist($factura);
        $this->entityManager->flush();
        return $factura;
    }

    public function getTotalFacturas()
    {
        $facturas = $this->getFacturas();
        return COUNT($facturas);
    }

    public function remove($factura)
    {
        parent::remove($factura->getTransaccion());
        $this->entityManager->remove($factura);
        $this->entityManager->flush();
    }

    public function getPresupuestoPrevio($numPresupuesto){
        return $this->entityManager->getRepository(Presupuesto::class)->findOneBy(['numero'=>$numPresupuesto]);
    }

    // public function cambiarEstadoTransaccion($idTransaccion, $estado){
    //     $transaccion = $this->getTransaccionId($idTransaccion);

    //     $transaccion->setEstado($estado);
    //     $this->revierteFechas($transaccion);
    //     if (strtoupper($estado)=="ANULADO"){
    //         $this->cuentaCorrienteManager->remove($transaccion);
    //     }
    //     $this->entityManager->flush();
    // }

    public function cambiarEstadoTransaccion($idTransaccion, $estado){
        $transaccion = $this->getTransaccionId($idTransaccion);
        if ($estado=="ANULADO"){
            // $this->devolverStock($items);
            $items = $transaccion->getBienesTransacciones();
            foreach($items as $item){
                $bien= $item->getBien();
                if (strtoupper($bien->getTipo())=="PRODUCTO"){
                    $stock = $bien->getStock();
                    $stock = $stock + $item->getCantidad();
                    $bien->setStock($stock);
                }
            }
            $this->cuentaCorrienteManager->remove($transaccion);
        }
        $transaccion->setEstado($estado);
        $this->entityManager->flush();
    }

    public function actualizaFechas($data) {
        $tipo_persona = $data['tipo_persona'];
        $fecha_transaccion = \DateTime::createFromFormat('d/m/Y', $data['fecha_transaccion']);
        $fecha_vencimiento = \DateTime::createFromFormat('d/m/Y', $data['fecha_transaccion']);

        // Ultimo Contacto
        if (is_null($tipo_persona->getFechaUltimoContacto())) {
            $tipo_persona->setFechaUltimoContacto($fecha_transaccion);
        } else {
            if ($fecha_transaccion > $tipo_persona->getFechaUltimoContacto()) {
                $tipo_persona->setFechaUltimoContacto($fecha_transaccion);
            }
        }

        // Fecha Compra
        if (is_null($tipo_persona->getFechaCompra())) {
            $tipo_persona->setFechaCompra($fecha_transaccion);
        }
       
        // Fecha Ultimo Pago
        if (is_null($tipo_persona->getFechaUltimoPago())) {
            $tipo_persona->setFechaUltimoPago($fecha_transaccion);
        }
        elseif ($fecha_transaccion > $tipo_persona->getFechaUltimoPago()) {
            $tipo_persona->setFechaUltimoPago($fecha_transaccion); 
        }
        
        // Fecha Vencimiento
        $empresa = $this->entityManager->getRepository(Empresa::class)->find(1);
        $interval = 'P' . $empresa->getParametro_vencimiento() . 'M';
        if(strtoupper($tipo_persona->getPersona()->getTipo())=="CLIENTE") {
              $fecha_vencimiento->add(new DateInterval($interval));
            if ($fecha_vencimiento > $tipo_persona->getVencimiento()) {
                $tipo_persona->setVencimiento($fecha_vencimiento);
            }
        }
      
        
    }

    public function revierteFechas($transaccion) {
        
        $id_persona = $transaccion->getPersona()->getId();       
        if ($transaccion->getPersona()->getTipo() == "CLIENTE") {
            $tipo_persona = $this->entityManager->getRepository(Cliente::class)->findOneBy(['persona' => $id_persona]);
        } elseif ($transaccion->getPersona()->getTipo() == "PROVEEDOR") {
            $tipo_persona = $this->entityManager->getRepository(Proveedor::class)->findOneBy(['persona' => $id_persona]);
        }
   
        $tipo_persona->setCiudad("ZZZZZZZZZZZZZ");

        if($transaccion->getFecha_transaccion()) {
            $fecha_anulada = $transaccion->getFecha_transaccion()->format('d/m/Y');
        } else {
            $fecha_anulada = null;
        }
        
        if($tipo_persona->getFechaCompra()){
            $fecha_compra = $tipo_persona->getFechaCompra()->format('d/m/Y');
        } else {
            $fecha_compra = null;
        }

        if($tipo_persona->getFechaUltimoPago()){
            $fecha_ultimo_pago = $tipo_persona->getFechaUltimoPago()->format('d/m/Y');
        } else {
            $fecha_ultimo_pago = null;
        }

        // * * * BREAK
        if (is_null($fecha_compra)){
            return;
        }
        // * * * BREAK
        if( ($fecha_anulada < $fecha_ultimo_pago) && ($fecha_anulada != $fecha_compra)){
            return;
        }

        $fecha_factura_anterior = null;
        $fecha_vencimiento = null;
        $facturas_previos = $this->entityManager->getRepository(Transaccion::class)->findBy(['persona' => $id_persona, 
                                                'tipo_transaccion' => "Factura", 'estado'=>"ACTIVO"], 
                                                array('fecha_transaccion' => 'DESC'));

        foreach ($facturas_previos as $factura){
            if($factura->getFecha_transaccion()){
                $fecha_factura = $factura->getFecha_transaccion()->format('d/m/Y');
            } else {
                $fecha_factura = null;
            }

            if(($fecha_anulada >= $fecha_factura) && ($transaccion->getId() != $factura->getId())) {
                $fecha_factura_anterior = $fecha_factura;
                $fecha_vencimiento = $fecha_factura;
                break;        
            } elseif($transaccion->getId() != $factura->getId()) {
                $fecha_venta_nueva = $fecha_factura;
            }
        }

        // Fecha Compra
        if( (!is_null($fecha_venta_nueva)) && (is_null($fecha_factura_anterior)) ){
            $tipo_persona->setFechaCompra(\DateTime::createFromFormat('d/m/Y', $fecha_venta_nueva));
            return;
        } elseif($fecha_anulada == $fecha_compra){
            if($fecha_factura_anterior){
                $tipo_persona->setFechaCompra(\DateTime::createFromFormat('d/m/Y', $fecha_factura_anterior));
            } else {
                $tipo_persona->setFechaCompra(null);
            }
        }

        // Fecha Ultimo Pago
        if($fecha_factura_anterior){
            $tipo_persona->setFechaUltimoPago(\DateTime::createFromFormat('d/m/Y', $fecha_factura_anterior));
        } else {
            $tipo_persona->setFechaUltimoPago(null);
        }

        // Fecha Vencimiento
        if(!is_null($fecha_vencimiento)){
            $fecha_vencimiento = \DateTime::createFromFormat('d/m/Y', $fecha_vencimiento);
            $empresa = $this->entityManager->getRepository(Empresa::class)->find(1);
            $interval = 'P' . $empresa->getParametro_vencimiento() . 'M';
            $fecha_vencimiento->add(new DateInterval($interval));
            $tipo_persona->setVencimiento($fecha_vencimiento);
        } else {
            $tipo_persona->setVencimiento(null);
        }
        
    }
}