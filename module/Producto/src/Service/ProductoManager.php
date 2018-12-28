<?php
namespace Producto\Service;

use DBAL\Entity\Producto;
use DBAL\Entity\CategoriaProducto;
use Producto\Form\ProductoForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing productos
 * and changing producto password.
 */
class ProductoManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;  
    
    private $ivaManager;

    private $categoriaProductoManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $ivaManager, $categoriaProductoManager) 
    {
        $this->entityManager = $entityManager;
        $this->ivaManager= $ivaManager;
        $this->categoriaProductoManager= $categoriaProductoManager;
    }
    
     public function getProductos(){
        $productos=$this->entityManager->getRepository(Producto::class)->findAll();
        return $productos;
    }
    
    public function getProductoId($id){
       return $this->entityManager->getRepository(Producto::class)
                ->find($id);
    }

    /**
     * This method adds a new producto.
     */
    public function addProducto($data) {
        $producto = new Producto();
        $this->addDatosParticularesProducto($producto, $data);
        $this->addDatosEconomicosProducto($producto, $data);
        // Apply changes to database.
        $this->entityManager->persist($producto);
        $this->entityManager->flush();
        return $producto;
    }
    
    /**
     * This method updates data of an existing producto.
     */
    public function updateProducto($producto, $data) {
        $this->addDatosParticularesProducto($producto, $data);
        $this->addDatosEconomicosProducto($producto, $data);          
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    private function addDatosParticularesProducto($producto, $data){
        $producto->setNombre($data['nombre']);
        $producto->setDescripcion($data['descripcion']);
        if($data['categoria'] == "-1"){
            $producto->setCategoria(null);
        } else {
            // Obtener Entidad con id y pasarla
            $producto->setCategoria($this->categoriaProductoManager
                                    ->getCategoriaProductoId($data['categoria']));
        }
        if($data['proveedor'] == "-1"){
            $producto->setProveedor(null);
        } else {
            // Obtener Entidad con id y pasarla
            $producto->setProveedor($data['proveedor']);
        }
        $producto->setMarca($data['marca']);
        $producto->setPresentacion($data['presentacion']);
        $producto->setStock($data['stock']);
        $producto->setReposicion($data['reposicion']);
        $producto->setCodigo_producto($data['codigo_producto']);
        $producto->setCodigo_barras($data['codigo_barras']);
    }

    private function addDatosEconomicosProducto($producto, $data){
        $producto->setPrecio_compra($data['precio_compra']);
        $producto->setCostos_directos($data['costos_directos']);
        $producto->setGastos_directos($data['gastos_directos']);
        $producto->setPrecio_compra_total($data['precio_compra_total']);
        $producto->setContribucion_marginal_valor($data['cm_valor']);
        $producto->setContribucion_marginal_porcentual($data['cm_porcentual']);
        $producto->setPrecio_venta($data['precio_venta']);
        $producto->setPrecio_venta_dto($data['precio_venta_dto']);
        $producto->setDescuento($data['descuento']);
        $producto->setIva($this->ivaManager->getIvaPorValor($data['iva']));
        $producto->setIva_gravado($data['iva_gravado']);
        $producto->setPrecio_final_iva($data['precio_final_iva']);
        $producto->setPrecio_final_iva_dto($data['precio_final_iva_dto']);
        // Entidad
        // $producto->setMoneda($data['moneda']);
    }

    public function createForm(){
        return new ProductoForm('create', $this->entityManager,null);
    }
    
   public function formValid($form, $data){
       $form->setData($data);
       return $form->isValid();  
    }
       
   
   public function getFormForProducto($producto) {

        if ($producto == null) {
            return null;
        }
        $form = new ProductoForm('update', $this->entityManager, $producto);
        return $form;
    }
    
    
    public function getFormEdited($form, $producto){
        $form->setData(array(
                    'nombre_producto'=>$producto->getNombre(),
                    'version_producto'=>$producto->getVersion(),                    
                ));
    }
    
    public function eliminarCategoriaProductos($id) {
        $entityManager = $this->entityManager;
        $productos = $this->entityManager->getRepository(Producto::class)->findBy(['categoria'=>$id]);
        foreach ($productos as $producto) {
            $producto->setCategoria(null);
        }
        $entityManager->flush();
    }

    public function removeProducto($producto) {
            $this->entityManager->remove($producto);
            $this->entityManager->flush();  
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Producto::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    public function getCategoriaProducto($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(CategoriaProducto::class)
                            ->findOneBy(['id' => $id]);
        }
        return $this->entityManager
                        ->getRepository(CategoriaProducto::class)
                        ->findAll();
    }

    public function eliminarIvas($id){
        $productos = $this->entityManager->getRepository(Producto::class)->findBy(['iva'=>$id]);
        foreach($productos as $producto){
            $producto->setIva(null);
        }
    }
} 