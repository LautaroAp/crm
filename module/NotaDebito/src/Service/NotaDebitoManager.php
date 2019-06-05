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
    private $tipoFacturaManager;

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
        $tipoFacturaManager

    ) {
        parent::__construct($entityManager, $personaManager, $bienesTransaccionManager, $ivaManager, $formaPagoManager, $formaEnvioManager, $monedaManager, $bienesManager, $cuentaCorrienteManager);
        $this->entityManager = $entityManager;
        $this->tipoFacturaManager = $tipoFacturaManager;
        $this->tipo = "NOTA DE DEBITO";
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
        if (isset($data['concepto'])) {
            $notaDebito->setConcepto($data['concepto']);
        }
        if (isset($data['total_letras'])) {
            $notaDebito->setImporte_letras($data['total_letras']);
        }
        if (isset($data['tipo_factura'])){
            if ($data['tipo']!=-1){
                $idTipo = $data['tipo_factura'];
                $tipo = $this->tipoFacturaManager->getTipoFactura($idTipo);
                $notaDebito->setTipo($tipo);
            }
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
        $this->cuentaCorrienteManager->add($transaccion);
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
        if (strtoupper($estado)=="ANULADO"){
            $this->cuentaCorrienteManager->remove($transaccion);
        }
        $this->entityManager->flush();
    }
}