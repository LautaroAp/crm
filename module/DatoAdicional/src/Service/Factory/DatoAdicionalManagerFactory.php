<?php
namespace DatoAdicional\Service\Factory;

use Interop\Container\ContainerInterface;
use DatoAdicional\Service\DatoAdicionalManager;

/**
 * This is the factory class for DatoAdicionalManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class DatoAdicionalManagerFactory
{
    /**
     * This method creates the DatoAdicionalManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new DatoAdicionalManager($entityManager);
    }
}
