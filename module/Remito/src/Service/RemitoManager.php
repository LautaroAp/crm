<?php

namespace Remito\Service;

use DBAL\Entity\Moneda;
use DBAL\Entity\Remito;
use DBAL\Entity\Pedido;
use DBAL\Entity\Persona;
use DBAL\Entity\Bienes;
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
    public function __construct( 
        $entityManager,
        $monedaManager,
        $personaManager,
        $bienesTransaccionManager,
        $ivaManager,
        $formaPagoManager,
        $formaEnvioManager,
        $bienesManager, 
        $cuentaCorrienteManager) {
        parent::__construct($entityManager, $personaManager, $bienesTransaccionManager, $ivaManager, $formaPagoManager, 
                            $formaEnvioManager, $monedaManager, $bienesManager, $cuentaCorrienteManager);
        $this->entityManager = $entityManager;
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
            ->findOneBy(['transaccion' => $id]);
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
        // $this->entityManager->flush();
        // $this->setNumeroCuentaCorriente($transaccion, $remito->getNumero());
        $this->entityManager->flush();

        return $remito;
    }

    public function add($data)
    {
        $transaccion = parent::add($data);
        $remito = new Remito();
        $remito = $this->setData($remito, $data, $transaccion);
        $this->cuentaCorrienteManager->add($transaccion);

        // Apply changes to database.
        $this->entityManager->persist($remito);
        $this->entityManager->flush();
        return $remito;
    }

    private function setData($remito, $data, $transaccion){
        $remito->setTransaccion($transaccion);
        if (isset($data['numero_remito'])){
            $remito->setNumero($data['numero_remito']);
            $transaccion->setNumeroTransaccionTipo($data['numero_remito']);
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


    public function getPedidoPrevio($numPedido){
        return $this->entityManager->getRepository(Pedido::class)->findOneBy(['numero'=>$numPedido]);
    }

    public function getPedidoPrevioFromTransaccion($transaccionPrevia){
        if(!is_null($transaccionPrevia)){
             $idTransaccionPrevia= $transaccionPrevia->getId();
            return $this->entityManager->getRepository(Pedido::class)->findOneBy(['transaccion'=>$idTransaccionPrevia]);
        }
        return null;
    }
       

    public function devolverStock($items){
        foreach ($items as $item){
            $bien = $item->getBien();
            $this->bienesManager->addStock($bien,$item->getCantidad());
        }
    }
    
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
        }
        $transaccion->setEstado($estado);
        $this->entityManager->flush();
    }
}
