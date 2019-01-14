<?php

namespace Proveedor\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Proveedor\Service\ProveedorManager;
use Usuario\Service\UsuarioManager;
use Persona\Service\PersonaManager;

class ProveedorManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $usuarioManager = $container->get(UsuarioManager::class);
        $personaManager = $container->get(PersonaManager::class);
        return new ProveedorManager($entityManager, $usuarioManager, $personaManager, $ganaderiaManager);
    }

}
