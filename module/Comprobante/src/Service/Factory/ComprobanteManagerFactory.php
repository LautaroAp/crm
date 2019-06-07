<?php
namespace Comprobante\Service\Factory;

use Interop\Container\ContainerInterface;
use Comprobante\Service\ComprobanteManager;

/**
 * This is the factory class for ComprobanteManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ComprobanteManagerFactory
{
    /**
     * This method creates the ComprobanteManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new ComprobanteManager($entityManager);
    }
}
