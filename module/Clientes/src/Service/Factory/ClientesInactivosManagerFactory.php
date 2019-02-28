<?php

namespace Clientes\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Clientes\Service\ClientesInactivosManager;
use Usuario\Service\UsuarioManager;
use Persona\Service\PersonaManager;

class ClientesInactivosManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $usuarioManager = $container->get(UsuarioManager::class);
        $personaManager = $container->get(PersonaManager::class);
        // Instantiate the service and inject dependencies
        return new ClientesInactivosManager($entityManager,$usuarioManager, 
        $personaManager);
    }

}
