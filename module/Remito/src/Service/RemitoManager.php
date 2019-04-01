<?php

namespace Remito\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\Remito;
use DBAL\Entity\Persona;
use DBAL\Entity\BienesTransacciones;
use DBAL\Entity\Transaccion;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Transaccion\Service\TransaccionManager;
use DateInterval;

/**
 * Esta clase se encarga de obtener y modificar los datos de los remitos 
 * 
 */
class RemitoManager extends TransaccionManager{

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
    public function __construct($entityManager, $monedaManager, $personaManager, $bienesTransaccionManager,
    $ivaManager, $formaPagoManager) {
        parent::__construct($entityManager, $personaManager, $bienesTransaccionManager, $ivaManager, $formaPagoManager);
        $this->entityManager = $entityManager;
        $this->monedaManager = $monedaManager;
        $this->tipo = "REMITO";
    }

    public function getRemitos() {
        $remitos = $this->entityManager->getRepository(Remito::class)->findAll();
        return $remitos;
    }

    public function getRemitoId($id) {
        return $this->entityManager->getRepository(Remito::class)
                        ->find($id);
    }

    public function getRemitoFromTransaccionId($id) {
        return $this->entityManager->getRepository(Remito::class)
                        ->findOneBy(['transaccion'=>$id]);
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Remito::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    /**
     * This method adds a new remito.
     */
    public function addRemito($data, $items) {
        //llamo a add de la transaccion, retorna una transaccion que se le setea al remito
        $transaccion = parent::add($data,$items);
        
        $remito = new Remito();
        $remito=$this->setData($remito, $data, $transaccion);

        $this->entityManager->persist($remito);
        $this->entityManager->flush();
        return $remito;
    }

    private function setData($remito, $data, $transaccion){
        $remito->setTransaccion($transaccion);
        if (isset($data['numero_remito'])){
            $remito->setNumero($data['numero_remito']);
        }
        $moneda=null;
        if ($data['moneda']!= '-1'){
            $moneda = $this->monedaManager->getMonedaId($data['moneda']); 
        }
        $remito->setMoneda($moneda);
        $fecha_entrega=null;

        if (isset($data['fecha_entrega'])){
            $fecha_entrega = \DateTime::createFromFormat('d/m/Y', $data['fecha_entrega']); 
            $remito->setFecha_entrega($fecha_entrega);
        }
       
        if (isset($data['forma_envio'])){
            $remito->setForma_envio($data['forma_envio']);
        }
        if (isset($data['ingresos_brutos'])){
            $remito->setIngresos_brutos($data['ingresos_brutos']);
        }
        if (isset($data['lugar_entrega'])){
            $remito->setLugar_entrega($data['lugar_entrega']);
        }
       return $remito;
    }

    /**
     * This method updates data of an existing remito.
     */
    public function edit($remito, $data) {
        $transaccion = parent::edit($remito->getTransaccion(), $data);
        $remito = $this->setData($remito, $data, $transaccion);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function getTotalRemitos(){
        $remitos = $this->getRemitos();
        return COUNT($remitos);
    }

    public function remove($remito) {
        parent::remove($remito->getTransaccion());
        $this->entityManager->remove($remito);
        $this->entityManager->flush();
    }


}
