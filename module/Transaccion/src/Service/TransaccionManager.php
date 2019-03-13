<?php

namespace Transaccion\Service;

use DBAL\Entity\Transaccion;
use DBAL\Entity\Categoria;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los servicios 
 * 
 */
class TransaccionManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $proveedorManager;
    private $ivaManager;
    private $categoriaManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $ivaManager, $categoriaManager, $proveedorManager) {
        $this->entityManager = $entityManager;
        $this->proveedorManager= $proveedorManager;
        $this->ivaManager= $ivaManager;
        $this->categoriaManager= $categoriaManager;
    }

    public function getTransaccion() {
        $transaccion = $this->entityManager->getRepository(Transaccion::class)->findAll();
        return $transaccion;
    }

    public function getTransaccionId($id) {
        return $this->entityManager->getRepository(Transaccion::class)
                        ->find($id);
    }

    public function getTabla($tipo) {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Transaccion::class)->
        findBy(['tipo'=>$tipo])); // An object repository implements Selectable
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

    
    /**
     * This method adds a new servicio.
     */
    public function addTransaccion($data) {
        $transaccion = new Transaccion();
        $transaccion=$this->setData($transaccion, $data);
        $this->entityManager->persist($transaccion);
        $this->entityManager->flush();
        return $transaccion;
    }

    // private function setData($transaccion, $data){
    //     $transaccion->setNombre($data['nombre']);
    //     $transaccion->setDescripcion($data['descripcion']);
    //     if($data['categoria'] == "-1"){
    //         $transaccion->setCategoria(null);
    //     } else {
    //         // Obtener Entidad con id y pasarla
    //         $transaccion->setCategoria($this->categoriaManager->getCategoriaId($data['categoria']));
    //     }
    //     if($data['proveedor'] == "-1"){
    //         $transaccion->setProveedor(null);
    //     } else {            
    //         $prov=$this->proveedorManager->getProveedor($data['proveedor']);
    //         $transaccion->setProveedor($prov);
    //     }
    //     $transaccion->setPrecio($data['precio_venta']);
    //     $transaccion->setIva_gravado($data['iva_total']);
    //     $iva=$this->ivaManager->getIva($data['iva']);
    //     $transaccion->setIva($iva);
    //     $transaccion->setPrecio($data['precio_venta']);
    //     $transaccion->setDescuento($data['descuento']);
    //     $transaccion->setPrecio_final_dto($data['precio_publico_dto']);
    //     $transaccion->setPrecio_final_iva($data['precio_publico_iva']);
    //     $transaccion->setPrecio_final_iva_dto($data['precio_publico_iva_dto']);
    //     $transaccion->setTipo($data['tipo']);
    //     //MONEDA
    //     return $transaccion;
    // }

    /**
     * This method updates data of an existing servicio.
     */
    public function updateBien($transaccion, $data) {
        $transaccion=$this->setData($transaccion, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removeBien($transaccion) {
        $this->entityManager->remove($transaccion);
        $this->entityManager->flush();
    }


    // public function eliminarCategoriaTransaccion($id){
    //     $entityManager = $this->entityManager;
    //     $transaccion = $this->entityManager->getRepository(Transaccion::class)->findBy(['categoria'=>$id]);
    //     foreach ($transaccion as $s) {
    //         $s->setCategoria(null);
    //     }
    //     $entityManager->flush();
    // }

    // public function getCategoriasTransaccion($tipo = null) {
    //     if (isset($tipo)) {
    //         return $this->entityManager
    //                         ->getRepository(Categoria::class)
    //                         ->findBy(['tipo' => $tipo]);
    //     }
    //     return $this->entityManager
    //                     ->getRepository(Categoria::class)
    //                     ->findAll();
    // }

    // public function eliminarIvas($id){
    //     $transaccion = $this->entityManager->getRepository(Transaccion::class)->findBy(['iva'=>$id]);
    //     foreach($transaccion as $transaccion){
    //         $transaccion->setIva(null);
    //     }
    // }

}
