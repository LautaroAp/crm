<?php

namespace BienesTransacciones\Service;

use DBAL\Entity\BienesTransacciones;
use DBAL\Entity\Categoria;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los servicios 
 * 
 */
class BienesTransaccionesManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $proveedorManager;
    private $ivaManager;
    private $categoriaManager;
    private $bienesManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $ivaManager, $categoriaManager, $proveedorManager,
    $bienesManager) {
        $this->entityManager = $entityManager;
        $this->proveedorManager= $proveedorManager;
        $this->ivaManager= $ivaManager;
        $this->categoriaManager= $categoriaManager;
        $this->bienesManager = $bienesManager;
    }

    public function getBienesTransacciones() {
        $bienesTransacciones = $this->entityManager->getRepository(BienesTransacciones::class)->findAll();
        return $bienesTransacciones;
    }

    public function getBienId($id) {
        return $this->entityManager->getRepository(BienesTransacciones::class)
                        ->find($id);
    }

    public function getTabla($tipo) {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(BienesTransacciones::class)->
        findBy(['tipo'=>$tipo])); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function getBienesTransaccionesTipo($tipo){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('B')
                     ->from(BienesTransacciones::class, 'B')
                     ->where("B.tipo LIKE ?$t")->setParameter("$t", $tipo);
        return $queryBuilder->getQuery();
    }

    
    /**
     * This method adds a new servicio.
     */
    public function addBienTransaccion($data) {
        $bienTransaccion = new BienesTransacciones();
        $bienTransaccion=$this->setData($bienTransaccion, $data);
        $this->entityManager->persist($bienTransaccion);
        $this->entityManager->flush();
        return $bienTransaccion;
    }

    public function add($bienTransaccion){
        $this->entityManager->merge($bienTransaccion);
        $this->entityManager->flush();
        return $bienTransaccion;
    }
    
    private function setData($bienTransaccion, $data){
        $bienTransaccion->setNombre($data['nombre']);
        $bienTransaccion->setDescripcion($data['descripcion']);
        if($data['categoria'] == "-1"){
            $bienTransaccion->setCategoria(null);
        } else {
            // Obtener Entidad con id y pasarla
            $bienTransaccion->setCategoria($this->categoriaManager->getCategoriaId($data['categoria']));
        }
        if($data['proveedor'] == "-1"){
            $bienTransaccion->setProveedor(null);
        } else {            
            $prov=$this->proveedorManager->getProveedor($data['proveedor']);
            $bienTransaccion->setProveedor($prov);
        }
        $bienTransaccion->setPrecio($data['precio_venta']);
        $bienTransaccion->setIva_gravado($data['iva_total']);
        $iva=$this->ivaManager->getIva($data['iva']);
        $bienTransaccion->setIva($iva);
        $bienTransaccion->setPrecio($data['precio_venta']);
        $bienTransaccion->setDescuento($data['descuento']);
        $bienTransaccion->setPrecio_final_dto($data['precio_publico_dto']);
        $bienTransaccion->setPrecio_final_iva($data['precio_publico_iva']);
        $bienTransaccion->setPrecio_final_iva_dto($data['precio_publico_iva_dto']);
        $bienTransaccion->setTipo($data['tipo']);
        //MONEDA
        return $bienTransaccion;
    }

    /**
     * This method updates data of an existing servicio.
     */
    public function updateBienTransaccion($bienTransaccion, $data) {
        $bienTransaccion=$this->setData($bienTransaccion, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removeBienTransaccion($bienTransaccion) {
        $this->entityManager->remove($bienTransaccion);
        $this->entityManager->flush();
    }


    public function eliminarCategoriaBienesTransacciones($id){
        $entityManager = $this->entityManager;
        $bienesTransacciones = $this->entityManager->getRepository(BienesTransacciones::class)->findBy(['categoria'=>$id]);
        foreach ($bienesTransacciones as $s) {
            $s->setCategoria(null);
        }
        $entityManager->flush();
    }

    public function getCategoriasBienesTransacciones($tipo = null) {
        if (isset($tipo)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findBy(['tipo' => $tipo]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findAll();
    }

    public function eliminarIvas($id){
        $bienesTransacciones = $this->entityManager->getRepository(BienesTransacciones::class)->findBy(['iva'=>$id]);
        foreach($bienesTransacciones as $bienTransaccion){
            $bienTransaccion->setIva(null);
        }
    }

    public function bienTransaccionFromArray($array){
        $bienTransaccion = new BienesTransacciones();
        // var_dump($array);
        if (isset($array['Bien']['Id'])){
            $bien = $this->bienesManager->getBienId($array['Bien']['Id']);
        }
        else{
            $bien = $this->bienesManager->getBienId($array['Bien']);
        }
        $bienTransaccion->setBien($bien);
        $bienTransaccion->setCantidad($array['Cantidad']);
        $bienTransaccion->setDescuento($array['Descuento']);
        if (isset($array['IVA']['Id'])){
            $iva = $this->ivaManager->getIva($array['IVA']['Id']);
        }
        else{
            $iva=$this->ivaManager->getIva($array['IVA']);
        }
        $bienTransaccion->setIva($iva);
        $subtotal = $array['Subtotal'];
        $bienTransaccion->setSubtotal($subtotal);
        return $bienTransaccion;
    }

    public function borrarBienesTransacciones($bienesTransacciones){
        foreach ($bienesTransacciones as $bienTransaccion){
            $this->removeBienTransaccion($bienTransaccion);
        }
    }
}
