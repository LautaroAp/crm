<?php
namespace Ganaderia\Service\Factory;

use Interop\Container\ContainerInterface;
use Ganaderia\Service\GanaderiaManager;

/**
 * This is the factory class for GanaderiaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class GanaderiaManagerFactory
{
    /**
     * This method creates the GanaderiaManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new GanaderiaManager($entityManager, $viewRenderer, $config);
    }
}
