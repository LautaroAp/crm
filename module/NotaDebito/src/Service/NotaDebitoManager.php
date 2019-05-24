<?php

namespace NotaDebito\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\NotaDebito;
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
 * Esta clase se encarga de obtener y modificar los datos de los notaDebitos 
 * 
 */
class NotaDebitoManager extends TransaccionManager
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
        $bienesManager,
        $cuentaCorrienteManager
    ) {
        parent::__construct($entityManager, $personaManager, $bienesTransaccionManager, $ivaManager, $formaPagoManager, $formaEnvioManager, $monedaManager, $bienesManager, $cuentaCorrienteManager);
        $this->entityManager = $entityManager;
        $this->tipo = "COBRO";
    }

    public function getNotaDebitos()
    {
        $notaDebitos = $this->entityManager->getRepository(NotaDebito::class)->findAll();
        return $notaDebitos;
    }

    public function getNotaDebitoId($id)
    {
        return $this->entityManager->getRepository(NotaDebito::class)
            ->find($id);
    }

    public function getNotaDebitoFromTransaccionId($id)
    {
        return $this->entityManager->getRepository(NotaDebito::class)
            ->findOneBy(['transaccion' => $id]);
    }

    public function getTabla()
    {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(NotaDebito::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }


    private function setData($notaDebito, $data, $transaccion)
    {
        $notaDebito->setTransaccion($transaccion);
        if (isset($data['numero_notaDebito'])) {
            $notaDebito->setNumero($data['numero_notaDebito']);
            $transaccion->setNumeroTransaccionTipo($data['numero_notaDebito']);
        }
        return $notaDebito;
    }

    /**
     * This method updates data of an existing notaDebito.
     */
    public function edit($notaDebito, $data)
    {
        $transaccion = parent::edit($notaDebito->getTransaccion(), $data);
        $notaDebito = $this->setData($notaDebito, $data, $transaccion);
        $this->actualizaFechas($data);

        // Apply changes to database.
        $this->entityManager->flush();
        $this->cuentaCorrienteManager->edit($transaccion);

        return true;
    }

    public function add($data)
    {
        $transaccion = parent::add($data);
        $notaDebito = new NotaDebito();
        $notaDebito = $this->setData($notaDebito, $data, $transaccion);
        $this->actualizaFechas($data);
        $this->cuentaCorrienteManager->add($transaccion);
        $transaccionPrevia = $transaccion->getTransaccionPrevia();
        $this->cuentaCorrienteManager->setNotaDebitodo($transaccionPrevia);
        // Apply changes to database.
        $this->entityManager->persist($notaDebito);
        $this->entityManager->flush();
        return $notaDebito;
    }

    public function getTotalNotaDebitos()
    {
        $notaDebitos = $this->getNotaDebitos();
        return COUNT($notaDebitos);
    }

    public function remove($notaDebito)
    {
        parent::remove($notaDebito->getTransaccion());
        $this->entityManager->remove($notaDebito);
        $this->entityManager->flush();
    }

    public function getPresupuestoPrevio($numPresupuesto){
        return $this->entityManager->getRepository(Presupuesto::class)->findOneBy(['numero'=>$numPresupuesto]);
    }

    public function cambiarEstadoTransaccion($idTransaccion, $estado){
        $transaccion = $this->getTransaccionId($idTransaccion);
        $transaccion->setEstado($estado);
        $this->revierteFechas($transaccion);
        if (strtoupper($estado)=="ANULADO"){
            $this->cuentaCorrienteManager->remove($transaccion);
        }
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

        $fecha_notaDebito_anterior = null;
        $fecha_vencimiento = null;
        $notaDebitos_previos = $this->entityManager->getRepository(Transaccion::class)->findBy(['persona' => $id_persona, 
                                                'tipo_transaccion' => "NotaDebito", 'estado'=>"ACTIVO"], 
                                                array('fecha_transaccion' => 'DESC'));

        foreach ($notaDebitos_previos as $notaDebito){
            if($notaDebito->getFecha_transaccion()){
                $fecha_notaDebito = $notaDebito->getFecha_transaccion()->format('d/m/Y');
            } else {
                $fecha_notaDebito = null;
            }

            if(($fecha_anulada >= $fecha_notaDebito) && ($transaccion->getId() != $notaDebito->getId())) {
                $fecha_notaDebito_anterior = $fecha_notaDebito;
                $fecha_vencimiento = $fecha_notaDebito;
                break;        
            } elseif($transaccion->getId() != $notaDebito->getId()) {
                $fecha_venta_nueva = $fecha_notaDebito;
            }
        }

        // Fecha Compra
        if( (!is_null($fecha_venta_nueva)) && (is_null($fecha_notaDebito_anterior)) ){
            $tipo_persona->setFechaCompra(\DateTime::createFromFormat('d/m/Y', $fecha_venta_nueva));
            return;
        } elseif($fecha_anulada == $fecha_compra){
            if($fecha_notaDebito_anterior){
                $tipo_persona->setFechaCompra(\DateTime::createFromFormat('d/m/Y', $fecha_notaDebito_anterior));
            } else {
                $tipo_persona->setFechaCompra(null);
            }
        }

        // Fecha Ultimo Pago
        if($fecha_notaDebito_anterior){
            $tipo_persona->setFechaUltimoPago(\DateTime::createFromFormat('d/m/Y', $fecha_notaDebito_anterior));
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