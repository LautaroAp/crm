<?php
namespace Servicio\Service\Factory;

use Interop\Container\ContainerInterface;
use Servicio\Service\ServicioManager;

/**
 * This is the factory class for ServicioManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ServicioManagerFactory
{
    /**
     * This method creates the ServicioManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new ServicioManager($entityManager, $viewRenderer, $config);
    }
}
