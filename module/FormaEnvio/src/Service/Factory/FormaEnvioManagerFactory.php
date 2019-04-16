<?php
namespace FormaEnvio\Service\Factory;

use Interop\Container\ContainerInterface;
use FormaEnvio\Service\FormaEnvioManager;

/**
 * This is the factory class for FormaEnvioManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class FormaEnvioManagerFactory
{
    /**
     * This method creates the FormaEnvioManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new FormaEnvioManager($entityManager);
    }
}
