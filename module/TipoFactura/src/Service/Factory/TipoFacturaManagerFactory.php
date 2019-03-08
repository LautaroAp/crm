<?php
namespace TipoFactura\Service\Factory;

use Interop\Container\ContainerInterface;
use TipoFactura\Service\TipoFacturaManager;

/**
 * This is the factory class for TipoFacturaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class TipoFacturaManagerFactory
{
    /**
     * This method creates the TipoFacturaManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new TipoFacturaManager($entityManager);
    }
}
