<?php

namespace Ejecutivo\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Ejecutivo\Service\EjecutivoInactivoManager;
use Persona\Service\PersonaManager;

class EjecutivoInactivoManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $personaManager = $container->get(PersonaManager::class);
        return new EjecutivoInactivoManager($entityManager, $personaManager);
    }

}
