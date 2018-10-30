<?php
namespace Ejecutivo\Service\Factory;

use Interop\Container\ContainerInterface;
use Ejecutivo\Service\EjecutivoInactivoManager;

/**
 * This is the factory class for EjecutivoInactivo service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class EjecutivoInactivoManagerFactory
{
    /**
     * This method creates the EjecutivoInactivo service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new EjecutivoInactivoManager($entityManager, $viewRenderer, $config);
    }
}
