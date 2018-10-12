<?php
namespace Producto\Service\Factory;

use Interop\Container\ContainerInterface;
use Producto\Service\ProductoManager;

/**
 * This is the factory class for ProductoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ProductoManagerFactory
{
    /**
     * This method creates the ProductoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new ProductoManager($entityManager, $viewRenderer, $config);
    }
}
