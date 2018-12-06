<?php

namespace Ejecutivo\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Ejecutivo\Service\EjecutivoManager;
use Persona\Service\PersonaManager;

class EjecutivoManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $personaManager = $container->get(PersonaManager::class);
        return new EjecutivoManager($entityManager, $personaManager);
    }

}
