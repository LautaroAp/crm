<?php
namespace Iva\Service\Factory;

use Interop\Container\ContainerInterface;
use Iva\Service\IvaManager;

/**
 * This is the factory class for IvaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class IvaManagerFactory
{
    /**
     * This method creates the IvaManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new IvaManager($entityManager, $viewRenderer, $config);
    }
}
