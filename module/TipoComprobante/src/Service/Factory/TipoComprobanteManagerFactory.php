<?php
namespace TipoComprobante\Service\Factory;

use Interop\Container\ContainerInterface;
use TipoComprobante\Service\TipoComprobanteManager;

/**
 * This is the factory class for TipoComprobanteManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class TipoComprobanteManagerFactory
{
    /**
     * This method creates the TipoComprobanteManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new TipoComprobanteManager($entityManager);
    }
}
