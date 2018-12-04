<?php
namespace Usuario\Service\Factory;

use Interop\Container\ContainerInterface;
use Usuario\Service\UsuarioManager;
use Persona\Service\PersonaManager;


/**
 * This is the factory class for UsuarioManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class UsuarioManagerFactory
{
    /**
     * This method creates the UsuarioManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
        $personaManager = $container->get(PersonaManager::class);
        return new UsuarioManager($entityManager, $viewRenderer, $config, $personaManager);
    }
}
