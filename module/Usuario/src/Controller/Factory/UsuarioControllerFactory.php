<?php
namespace Usuario\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Usuario\Controller\UsuarioController;
use Usuario\Service\UsuarioManager;
use Clientes\Service\ClientesManager;
use Persona\Service\PersonaManager;

/**
 * Esta clase crea una instancia de UsuarioController 
 *
 * @author SoftHuella 
 */
class UsuarioControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $usuarioManager = $container->get(UsuarioManager::class);
        $clientesManager = $container->get(ClientesManager::class);
        $personaManager = $container->get(PersonaManager::class);

        // Instantiate the service and inject dependencies      
        return new UsuarioController($entityManager, $usuarioManager, $clientesManager, $personaManager);
    }    
}
