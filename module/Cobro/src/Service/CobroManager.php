<?php

namespace Cobro\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\Cobro;
use DBAL\Entity\Presupuesto;
use DBAL\Entity\Persona;
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
     
        // $fecha_entrega = null;
        // if (isset($data['fecha_entrega'])) {
        //     $fecha_entrega = \DateTime::createFromFormat('d/m/Y', $data['fecha_entrega']);
        //     $cobro->setFecha_entrega($fecha_entrega);
        // }

        // if (isset($data['forma_envio'])) {
        //     $cobro->setForma_envio($data['forma_envio']);
        // }

        // if (isset($data['ingresos_brutos'])) {
        //     $cobro->setIngresos_brutos($data['ingresos_brutos']);
        // }
        // if (isset($data['lugar_entrega'])) {
        //     $cobro->setLugar_entrega($data['lugar_entrega']);
        // }

        return $cobro;
    }

    /**
     * This method updates data of an existing cobro.
     */
    public function edit($cobro, $data)
    {
        $transaccion = parent::edit($cobro->getTransaccion(), $data);
        $cobro = $this->setData($cobro, $data, $transaccion);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function add($data)
    {
        $transaccion = parent::add($data);
        $cobro = new Cobro();
        $cobro = $this->setData($cobro, $data, $transaccion);
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
        $this->entityManager->flush();
    }
}