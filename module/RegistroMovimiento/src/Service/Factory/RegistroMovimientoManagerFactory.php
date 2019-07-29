<?php
namespace RegistroMovimiento\Service\Factory;

use Interop\Container\ContainerInterface;
use RegistroMovimiento\Service\RegistroMovimientoManager;

/**
 * This is the factory class for RegistroMovimientoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class RegistroMovimientoManagerFactory
{
    /**
     * This method creates the RegistroMovimientoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');                        
        return new RegistroMovimientoManager($entityManager);
    }
}
