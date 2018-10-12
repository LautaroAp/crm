<?php
namespace Ejecutivo\Service\Factory;

use Interop\Container\ContainerInterface;
use Ejecutivo\Service\EjecutivoManager;

/**
 * This is the factory class for EjecutivoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class EjecutivoManagerFactory
{
    /**
     * This method creates the EjecutivoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new EjecutivoManager($entityManager, $viewRenderer, $config);
    }
}
