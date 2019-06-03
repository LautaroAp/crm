<?php
namespace Detalle\Service\Factory;

use Interop\Container\ContainerInterface;
use Detalle\Service\DetalleManager;

/**
 * This is the factory class for DetalleManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class DetalleManagerFactory
{
    /**
     * This method creates the DetalleManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

                        
        return new DetalleManager($entityManager);
    }
}
