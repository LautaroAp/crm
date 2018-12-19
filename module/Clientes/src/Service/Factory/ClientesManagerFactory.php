<?php

namespace Clientes\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Clientes\Service\ClientesManager;
use Usuario\Service\UsuarioManager;
use Persona\Service\PersonaManager;
use Ganaderia\Service\GanaderiaManager;

class ClientesManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $usuarioManager = $container->get(UsuarioManager::class);
        $personaManager = $container->get(PersonaManager::class);
        $ganaderiaManager = $container->get(GanaderiaManager::class);
        return new ClientesManager($entityManager, $usuarioManager, $personaManager, $ganaderiaManager);
    }

}
