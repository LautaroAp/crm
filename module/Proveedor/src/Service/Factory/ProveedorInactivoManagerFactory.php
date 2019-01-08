<?php

namespace Proveedor\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Proveedor\Service\ProveedorInactivosManager;
use Proveedor\Service\ProveedorManager;
use Usuario\Service\UsuarioManager;
use Persona\Service\PersonaManager;

class ProveedorInactivoManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $personaManager = $container->get(PersonaManager::class);
        // Instantiate the service and inject dependencies
        return new ProveedorInactivoManager($entityManager,
        $personaManager);
    }

}
