<?php
namespace Moneda\Service\Factory;

use Interop\Container\ContainerInterface;
use Moneda\Service\MonedaManager;

/**
 * This is the factory class for MonedaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class MonedaManagerFactory
{
    /**
     * This method creates the MonedaManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');                        
        return new MonedaManager($entityManager);
    }
}
