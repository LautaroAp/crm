<?php
namespace ProfesionCliente\Service\Factory;

use Interop\Container\ContainerInterface;
use ProfesionCliente\Service\ProfesionClienteManager;

/**
 * This is the factory class for ProfesionClienteManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ProfesionClienteManagerFactory
{
    /**
     * This method creates the ProfesionClienteManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new ProfesionClienteManager($entityManager, $viewRenderer, $config);
    }
}
