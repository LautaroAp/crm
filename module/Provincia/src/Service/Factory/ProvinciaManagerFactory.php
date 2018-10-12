<?php
namespace Provincia\Service\Factory;

use Interop\Container\ContainerInterface;
use Provincia\Service\ProvinciaManager;

/**
 * This is the factory class for PaisManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ProvinciaManagerFactory
{
    /**
     * This method creates the PaisManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new ProvinciaManager($entityManager, $viewRenderer, $config);
    }
}
