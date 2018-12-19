<?php
namespace CategoriaEvento\Service\Factory;

use Interop\Container\ContainerInterface;
use CategoriaEvento\Service\CategoriaEventoManager;

/**
 * This is the factory class for CategoriaEventoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CategoriaEventoManagerFactory
{
    /**
     * This method creates the CategoriaEventoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new CategoriaEventoManager($entityManager, $viewRenderer, $config);
    }
}
