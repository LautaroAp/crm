<?php

namespace NotaCredito\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\NotaCredito;
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
 * Esta clase se encarga de obtener y modificar los datos de los notaCreditos 
 * 
 */
class NotaCreditoManager extends TransaccionManager
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
    private $tipoComprobanteManager;

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
        $cuentaCorrienteManager,
        $tipoComprobanteManager
    ) {
        parent::__construct($entityManager, $personaManager, $bienesTransaccionManager, $ivaManager, $formaPagoManager, $formaEnvioManager, $monedaManager, $bienesManager, $cuentaCorrienteManager);
        $this->entityManager = $entityManager;
        $this->tipoComprobanteManager = $tipoComprobanteManager;
        $this->tipo = "NOTA DE CREDITO";
    }

    public function getNotaCreditos()
    {
        $notaCreditos = $this->entityManager->getRepository(NotaCredito::class)->findAll();
        return $notaCreditos;
    }

    public function getNotaCreditoId($id)
    {
        return $this->entityManager->getRepository(NotaCredito::class)
            ->find($id);
    }

    public function getNotaCreditoFromTransaccionId($id)
    {
        return $this->entityManager->getRepository(NotaCredito::class)
            ->findOneBy(['transaccion' => $id]);
    }

    public function getTabla()
    {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(NotaCredito::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }


    private function setData($notaCredito, $data, $transaccion)
    {
        $notaCredito->setTransaccion($transaccion);
        if (isset($data['numero_notaCredito'])) {
            $notaCredito->setNumero($data['numero_notaCredito']);
            $transaccion->setNumeroTransaccionTipo($data['numero_notaCredito']);
        }
        if (isset($data['concepto'])) {
            $notaCredito->setConcepto($data['concepto']);
        }
        if (isset($data['total_letras'])) {
            $notaCredito->setImporte_letras($data['total_letras']);
        }
        if (isset($data['tipo_comprobante'])){
            if ($data['tipo']!=-1){
                $idTipo = $data['tipo_comprobante'];
                $tipo = $this->tipoComprobanteManager->getTipoComprobante($idTipo);
                $notaCredito->setTipo_comprobante($tipo);
            }
        }
        return $notaCredito;
    }

    /**
     * This method updates data of an existing notaCredito.
     */
    public function edit($notaCredito, $data)
    {
        $transaccion = parent::edit($notaCredito->getTransaccion(), $data);
        $notaCredito = $this->setData($notaCredito, $data, $transaccion);
        // Apply changes to database.
        $this->entityManager->flush();
        $this->cuentaCorrienteManager->edit($transaccion);

        return true;
    }

    public function add($data)
    {
        $transaccion = parent::add($data);
        $notaCredito = new NotaCredito();
        $notaCredito = $this->setData($notaCredito, $data, $transaccion);
        $this->cuentaCorrienteManager->add($transaccion);
        // Apply changes to database.
        $this->entityManager->persist($notaCredito);
        $this->entityManager->flush();
        return $notaCredito;
    }

    public function getTotalNotaCreditos()
    {
        $notaCreditos = $this->getNotaCreditos();
        return COUNT($notaCreditos);
    }

    public function remove($notaCredito)
    {
        parent::remove($notaCredito->getTransaccion());
        $this->entityManager->remove($notaCredito);
        $this->entityManager->flush();
    }

    public function getPresupuestoPrevio($numPresupuesto){
        return $this->entityManager->getRepository(Presupuesto::class)->findOneBy(['numero'=>$numPresupuesto]);
    }

    public function cambiarEstadoTransaccion($idTransaccion, $estado){
        $transaccion = $this->getTransaccionId($idTransaccion);
        $transaccion->setEstado($estado);
        if (strtoupper($estado)=="ANULADO"){
            $this->cuentaCorrienteManager->remove($transaccion);
        }
        $this->entityManager->flush();
    }
}