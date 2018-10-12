<?php
namespace Producto\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Producto\Controller\ProductoController;
use Producto\Service\ProductoManager;

/**
 * Description of ProductoControllerFactory
 *
 * @author mariano
 */
class ProductoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $productoManager = $container->get(ProductoManager::class);
        // Instantiate the service and inject dependencies
        return new ProductoController($entityManager, $productoManager);
    }    
}
