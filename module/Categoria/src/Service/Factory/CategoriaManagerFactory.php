<?php
namespace Categoria\Service\Factory;

use Interop\Container\ContainerInterface;
use Categoria\Service\CategoriaManager;

/**
 * This is the factory class for CategoriaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CategoriaManagerFactory
{
    /**
     * This method creates the CategoriaManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new CategoriaManager($entityManager, $viewRenderer, $config);
    }
}
