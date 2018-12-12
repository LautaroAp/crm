<?php
namespace Producto\Service;

use DBAL\Entity\Producto;
use Producto\Form\ProductoForm;



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
    
    /**
     * PHP template renderer.
     * @var type 
     */
    private $viewRenderer;
    
    /**
     * Application config.
     * @var type 
     */
    private $config;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $viewRenderer, $config) 
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }
    
     public function getProductos(){
        $productos=$this->entityManager->getRepository(Producto::class)->findAll();
        return $productos;
    }
    
    public function getProductoId($id){
       return $this->entityManager->getRepository(Producto::class)
                ->find($id);
    }
  
    public function getProductoFromForm($form, $data){
        $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();
                $producto = $this->addProducto($data);
            }
        return $producto;
    }
    /**
     * This method adds a new producto.
     */
    public function addProducto($data) 
    {
        $producto = new Producto();
        $producto->setVersion($data['version_producto']);
        $producto->setNombre($data['nombre_producto']);        
        $this->entityManager->persist($producto);
        $this->entityManager->flush();
        return $producto;
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

    /**
     * This method updates data of an existing producto.
     */
    public function updateProducto($producto, $form) {       
        $data = $form->getData();
        $producto->setVersion($data['version_producto']);
        $producto->setNombre($data['nombre_producto']);           
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }
    
    public function eliminarCategoriaProductos($id) {
        $entityManager = $this->entityManager;
        $producto = $this->entityManager->getRepository(Producto::class)->findAll();
        foreach ($productos as $producto) {
            if (!is_null($producto->getId_categoria())) {
                if ($producto->getId_categoria() == $id) {
                    $producto->setId_categoria(null);
                }
            }
        }
        $entityManager->flush();
    }

    public function removeProducto($producto) {
            $this->entityManager->remove($producto);
            $this->entityManager->flush();  
    }
} 