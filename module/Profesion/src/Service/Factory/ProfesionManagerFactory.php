<?php
namespace Profesion\Service\Factory;

use Interop\Container\ContainerInterface;
use Profesion\Service\ProfesionManager;

/**
 * This is the factory class for ProfesionManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ProfesionManagerFactory
{
    /**
     * This method creates the ProfesionManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new ProfesionManager($entityManager, $viewRenderer, $config);
    }
}
