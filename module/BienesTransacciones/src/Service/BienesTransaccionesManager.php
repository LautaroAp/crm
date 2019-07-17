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
        if (isset($data['nombre'])){
            $bienTransaccion->setNombre($data['nombre']);
        }
        if (isset($data['descripcion'])){
            $bienTransaccion->setDescripcion($data['descripcion']);
        }
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
        if (isset($data['precio_venta'])){
            $bienTransaccion->setPrecio($data['precio_venta']);
        }
        if (isset($data['iva_total'])){
            $bienTransaccion->setIva_gravado($data['iva_total']);
            $iva=$this->ivaManager->getIva($data['iva']);
            $bienTransaccion->setIva($iva);
        }
        if (isset($data['precio_venta'])){
            $bienTransaccion->setPrecio($data['precio_venta']);
        }
        if (isset($data['descuento']) and $data['descuento']!=''){
            $bienTransaccion->setDescuento($data['descuento']);
        }   
        if (isset($data['detalle']) and $data['detalle']!=''){
            $bienTransaccion->setDetalle($data['detalle']);
        }         
        if (isset($data['precio_publico_dto'])){
            $bienTransaccion->setPrecio_final_dto($data['precio_publico_dto']);
        }
        if (isset($data['precio_publico_iva'])){
            $bienTransaccion->setPrecio_final_iva($data['precio_publico_iva']);
        }
        if (isset($data['precio_publico_iva_dto'])){
            $bienTransaccion->setPrecio_final_iva_dto($data['precio_publico_iva_dto']);
        }
        if (isset($data['tipo'])){
            $bienTransaccion->setTipo($data['tipo']);
        }
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
        $precioUnitario = 0; $cantidad=1; $valorIva=0; $valorDto=0;
        if (isset($array['Bien'])){
            if (isset($array['Bien']['Id'])){
                $bien = $this->bienesManager->getBienId($array['Bien']['Id']);
            }
            else{
                $bien = $this->bienesManager->getBienId($array['Bien']);
            }
            $bienTransaccion->setBien($bien);
        }
        if (isset($array['Bien']['Precio']) && $array['Bien']['Precio']!=''){
            $bienTransaccion->setPrecioOriginal($array['Bien']['Precio']);
            $precioUnitario = $array['Bien']['Precio'];
        }
        if (isset($array['Cantidad'])){
            $bienTransaccion->setCantidad($array['Cantidad']);
            $cantidad= $array['Cantidad'];
        }

        if (isset($array['Dto']) && $array['Dto']!=''){
            $bienTransaccion->setDescuento($array['Dto']);
            $valorDto = $array['Dto'];
        }
        if (isset($array['ImpDto'])){
            $importeTotal = $array['ImpDto'];
            $bienTransaccion->setImporteBonificacion($importeTotal);
        }

        if (isset($array['IVA'])){
            if (isset($array['IVA']['Id'])){
            $iva = $this->ivaManager->getIva($array['IVA']['Id']);
            }
            else{
                $iva=$this->ivaManager->getIva($array['IVA']);
            }        
            $bienTransaccion->setIva($iva);
            $valorIva= $iva->getValor();
        }
        // if (isset($array['ImpIva'])){
        //     $importeTotal = $array['ImpIva'];
        //     $bienTransaccion->setImporteIva($importeTotal);
        // }
        if (isset($array['ImpIVA'])){
            $importeTotal = $array['ImpIVA'];
            $bienTransaccion->setImporteIva($importeTotal);
        }
        if (isset($array['ImporteGravado'])){
            // if(($importeTotal != null) && ($importeTotal !='')){
                
            // }
            $importeTotal = $array['ImporteGravado'];
            $bienTransaccion->setImporte_gravado($importeTotal);
        }
        if (isset($array['ImporteNoGravado'])){
            $importeTotal = $array['ImporteNoGravado'];
            $bienTransaccion->setImporte_no_gravado($importeTotal);
        }
        if (isset($array['ImporteExento'])){
            $importeTotal = $array['ImporteExento'];
            $bienTransaccion->setImporte_exento($importeTotal);
        }

        if (isset($array['Subtotal'])){
            $importeTotal = $array['Subtotal'];
            $bienTransaccion->setSubtotal($importeTotal);
        }
        if (isset($array['Totales'])){
            $importeTotal = $array['Totales'];
            $bienTransaccion->setImporteTotal($importeTotal);
        }
        if (isset($array['Transaccion Previa'])){
            $subtotal = $array['Transaccion Previa']['Subtotal'];
            $bienTransaccion->setSubtotal($subtotal);
            $precioUnitario = $subtotal;
        }
        if (isset($array['Detalle'])){
            $detalle = $array['Detalle'];
            $bienTransaccion->setDetalle($detalle);
        }

        // $totalDto = $precioUnitario*$cantidad * $valorDto/100;
        // $subtotal = ($precioUnitario * $cantidad) * $totalDto;
        // $totalIva = $subtotal * $valorIva /100;

        // $bienTransaccion->setSubtotal($subtotal);
        // $bienTransaccion->setImporteBonificacion($totalDto);
        // $bienTransaccion->setImporteIva($totalIva);

        return $bienTransaccion;
    }

    public function borrarBienesTransacciones($bienesTransacciones){
        foreach ($bienesTransacciones as $bienTransaccion){
            $this->removeBienTransaccion($bienTransaccion);
        }
    }

}
