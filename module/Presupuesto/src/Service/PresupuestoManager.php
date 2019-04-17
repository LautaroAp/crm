<?php

namespace Presupuesto\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\Presupuesto;
use DBAL\Entity\Persona;
use DBAL\Entity\BienesTransacciones;
use DBAL\Entity\Transaccion;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Transaccion\Service\TransaccionManager;
/**
 * Esta clase se encarga de obtener y modificar los datos de los presupuestos 
 * 
 */
class PresupuestoManager extends TransaccionManager{

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    public function __construct(
        $entityManager,
        $monedaManager,
        $personaManager,
        $bienesTransaccionManager,
        $ivaManager,
        $formaPagoManager,
        $formaEnvioManager
    ) {
        parent::__construct($entityManager, $personaManager, $bienesTransaccionManager, $ivaManager, $formaPagoManager, $formaEnvioManager, $monedaManager);
        $this->entityManager = $entityManager;
        $this->tipo = "PRESUPUESTO";
    }


    public function getPresupuestos() {
        $presupuestos = $this->entityManager->getRepository(Presupuesto::class)->findAll();
        return $presupuestos;
    }

    public function getPresupuestoId($id) {
        return $this->entityManager->getRepository(Presupuesto::class)
                        ->find($id);
    }

    public function getPresupuestoFromTransaccionId($id)
    {
        return $this->entityManager->getRepository(Presupuesto::class)
            ->findOneBy(['transaccion' => $id]);
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Presupuesto::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    /**
     * This method adds a new presupuesto.
     */
    // public function addPresupuesto($data, $items) {
    //     //llamo a add de la transaccion, retorna una transaccion que se le setea al presupuesto
    //     $transaccion = parent::add($data, $items);
    //     $presupuesto = new Presupuesto();
    //     $presupuesto=$this->setData($presupuesto, $data, $transaccion);
    //     $this->entityManager->persist($presupuesto);
    //     $this->entityManager->flush();
    //     return $presupuesto;
    // }

    public function add($data)
    {
        $transaccion = parent::add($data);
        $presupuesto = new Presupuesto();
        $presupuesto = $this->setData($presupuesto, $data, $transaccion);
        // Apply changes to database.
        $this->entityManager->persist($presupuesto);
        $this->entityManager->flush();
        return $presupuesto;
    }

    private function setData($presupuesto, $data, $transaccion){
        $presupuesto->setTransaccion($transaccion);
        if (isset($data['numero_presupuesto'])){
            $presupuesto->setNumero($data['numero_presupuesto']);
        }
        return $presupuesto;
    }


    /**
     * This method updates data of an existing presupuesto.
     */
    public function edit($presupuesto, $data) {
        $transaccion = parent::edit($presupuesto->getTransaccion(), $data);
        $presupuesto = $this->setData($presupuesto, $data, $transaccion);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function getTotalPresupuestos(){
        $presupuestos = $this->getPresupuestos();
        return COUNT($presupuestos);
    }

    public function remove($presupuesto) {
        parent::remove($presupuesto->getTransaccion());
        $this->entityManager->remove($presupuesto);
        $this->entityManager->flush();
    }

}
