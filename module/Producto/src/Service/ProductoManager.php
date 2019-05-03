<?php
namespace Producto\Service;

use DBAL\Entity\Producto;
use DBAL\Entity\Categoria;
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
    private $proveedorManager;
    private $categoriaManager;
    private $bienesManager;
    private $tipo;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $ivaManager, $categoriaManager, $proveedorManager,
    $bienesManager) 
    {
        $this->entityManager = $entityManager;
        $this->ivaManager= $ivaManager;
        $this->categoriaManager= $categoriaManager;
        $this->proveedorManager= $proveedorManager;
        $this->tipo = "PRODUCTO";
        $this->bienesManager= $bienesManager;
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
        $this->addDatosParticularesProducto($producto, $data, $producto->getBien());
        $this->addDatosEconomicosProducto($producto, $data, $producto->getBien());
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
        $bien = $producto->getBien();
        $data['tipo'] = $this->tipo;
        if (isset($bien)) {
            $this->bienesManager->updateBien($bien, $data);
        }
        else {
            $bien = $this->bienesManager->addBien($data);
            $producto->setBien($bien);
        }
        $producto->setReposicion($data['reposicion']);
    }

    private function addDatosEconomicosProducto($producto, $data){
        $producto->setPrecio_compra($data['precio_compra']);
        $producto->setCostos_directos($data['costos_directos']);
        $producto->setGastos_directos($data['gastos_directos']);
        $producto->setPrecio_compra_total($data['precio_compra_total']);
        $producto->setContribucion_marginal_valor($data['cm_valor']);
        $producto->setContribucion_marginal_porcentual($data['cm_porcentual']);
    }

    public function getListaProveedores(){
        return $this->proveedorManager->getListaProveedores();
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
        $bien = $producto->getBien();
        $this->entityManager->remove($producto);
        $this->bienesManager->remove($bien);
        $this->entityManager->flush();  
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Producto::class));
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    public function getCategoriaProducto($id = null, $tipo) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findOneBy(['id' => $id, 'tipo'=>$tipo]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findBy(['tipo'=>$tipo]);
    }

    public function eliminarIvas($id){
        $productos = $this->entityManager->getRepository(Producto::class)->findBy(['iva'=>$id]);
        foreach($productos as $producto){
            $producto->setIva(null);
        }
    }
} 