<?php
namespace CategoriaProducto\Service\Factory;

use Interop\Container\ContainerInterface;
use CategoriaProducto\Service\CategoriaProductoManager;

/**
 * This is the factory class for CategoriaProductoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CategoriaProductoManagerFactory
{
    /**
     * This method creates the CategoriaProductoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new CategoriaProductoManager($entityManager, $viewRenderer, $config);
    }
}
