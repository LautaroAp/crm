<?php
namespace TipoEvento\Service\Factory;

use Interop\Container\ContainerInterface;
use TipoEvento\Service\TipoEventoManager;

/**
 * This is the factory class for TipoEventoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class TipoEventoManagerFactory
{
    /**
     * This method creates the TipoEventoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new TipoEventoManager($entityManager, $viewRenderer, $config);
    }
}
