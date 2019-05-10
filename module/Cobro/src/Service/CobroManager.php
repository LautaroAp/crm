<?php

namespace Cobro\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\Cobro;
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
 * Esta clase se encarga de obtener y modificar los datos de los cobros 
 * 
 */
class CobroManager extends TransaccionManager
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
        $bienesManager
    ) {
        parent::__construct($entityManager, $personaManager, $bienesTransaccionManager, $ivaManager, $formaPagoManager, $formaEnvioManager, $monedaManager, $bienesManager);
        $this->entityManager = $entityManager;
        $this->tipo = "COBRO";
    }

    public function getCobros()
    {
        $cobros = $this->entityManager->getRepository(Cobro::class)->findAll();
        return $cobros;
    }

    public function getCobroId($id)
    {
        return $this->entityManager->getRepository(Cobro::class)
            ->find($id);
    }

    public function getCobroFromTransaccionId($id)
    {
        return $this->entityManager->getRepository(Cobro::class)
            ->findOneBy(['transaccion' => $id]);
    }

    public function getTabla()
    {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Cobro::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }


    private function setData($cobro, $data, $transaccion)
    {
        $cobro->setTransaccion($transaccion);
        if (isset($data['numero_cobro'])) {
            $cobro->setNumero($data['numero_cobro']);
            $transaccion->setNumeroTransaccionTipo($data['numero_cobro']);
        }
        return $cobro;
    }

    /**
     * This method updates data of an existing cobro.
     */
    public function edit($cobro, $data)
    {
        $transaccion = parent::edit($cobro->getTransaccion(), $data);
        $cobro = $this->setData($cobro, $data, $transaccion);
        $this->actualizaFechas($data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function add($data)
    {
        $transaccion = parent::add($data);
        $cobro = new Cobro();
        $cobro = $this->setData($cobro, $data, $transaccion);
        $this->actualizaFechas($data);
        // Apply changes to database.
        $this->entityManager->persist($cobro);
        $this->entityManager->flush();
        return $cobro;
    }

    public function getTotalCobros()
    {
        $cobros = $this->getCobros();
        return COUNT($cobros);
    }

    public function remove($cobro)
    {
        parent::remove($cobro->getTransaccion());
        $this->entityManager->remove($cobro);
        $this->entityManager->flush();
    }

    public function getPresupuestoPrevio($numPresupuesto){
        return $this->entityManager->getRepository(Presupuesto::class)->findOneBy(['numero'=>$numPresupuesto]);
    }

    public function cambiarEstadoTransaccion($idTransaccion, $estado){
        $transaccion = $this->getTransaccionId($idTransaccion);
        $transaccion->setEstado($estado);
        $this->revierteFechas($transaccion);
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
        $fecha_vencimiento->add(new DateInterval($interval));
        if ($fecha_vencimiento > $tipo_persona->getVencimiento()) {
            $tipo_persona->setVencimiento($fecha_vencimiento);
        }
        
    }

    public function revierteFechas($transaccion) {
                
        // FILTRAR POR PERSONA  Y OBTENER CLIENTE O PROVEEDOR *****************************************************

        // $tipo_persona = $transaccion->getPersona()->getId();
        // $this->entityManager->getRepository(Cliente::class)->findOneBy(['id'=>$transaccion->getPersona()->getId()]);

        // if ($transaccion->getPersona()->getTipo() == "CLIENTE") {
        //     $tipo_persona = $this->entityManager->getRepository(Cliente::class)->findOneBy(['id'=>$transaccion->getPersona()->getId()]);
        // } elseif ($transaccion->getPersona()->getTipo() == "PROVEEDOR") {
        //     $tipo_persona = $this->entityManager->getRepository(Proveedores::class)->findOneBy(['id'=>$transaccion->getPersona()->getId()]);
        // }

        // NO ANDAAAAAAAAAAAAAAAAAAAA

        $id_persona = $transaccion->getPersona()->getId();

        // $transacciones = $this->getTransacciones();
        // $fecha_anulada = \DateTime::createFromFormat('d/m/Y', $transaccion->getFecha_transaccion());
        // $fecha_anulada = $transaccion->getFecha_transaccion();

        $tipo_persona = $this->entityManager->getRepository(Cliente::class)->findOneBy(['persona' => $id_persona]);

        // $tipo_persona->setFechaCompra($fecha_anulada);

        $tipo_persona->setCiudad("CIUDAD");
        $tipo_persona->setRazaManejo("YYYYY");

        $fecha_transaccion_anterior = null;
        $fecha_vencimiento_anterior = null;
    
        // foreach ($transacciones as $e) {
        //     if( ($e->getTipo() == "cobro") && ($e->getEstado() == "ACTIVO") ){
        //         $fecha_transaccion = \DateTime::createFromFormat('d/m/Y', $e->getFecha_transaccion());
        //         $fecha_vencimiento = \DateTime::createFromFormat('d/m/Y', $e->getFecha_transaccion());


                
        //         // SI ENCONTRO FECHA ANTERIOR
        //         if($fecha_anulada > $fecha_transaccion) {
        //             $fecha_transaccion_anterior = $fecha_transaccion;
        //             $fecha_vencimiento_anterior = $fecha_vencimiento;
        //         } else {
        //             break;
        //         }            
        //     }
        // }

        // // Fecha Compra
        // if($fecha_anulada == $tipo_persona->getFechaCompra()){
        //     $tipo_persona->setFechaCompra($fecha_transaccion_anterior);
        // }
        // // Fecha Ultimo Pago
        // $tipo_persona->setFechaUltimoPago($fecha_transaccion_anterior);
        // // Fecha Vencimiento
        // if(!is_null($fecha_vencimiento_anterior)){
        //     $empresa = $this->entityManager->getRepository(Empresa::class)->find(1);
        //     $interval = 'P' . $empresa->getParametro_vencimiento() . 'M';
        //     $fecha_vencimiento_anterior->add(new DateInterval($interval));
        //     $tipo_persona->setVencimiento($fecha_vencimiento_anterior);
        // }
    }
}