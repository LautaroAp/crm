<?php

namespace Comprobante\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Comprobante\Controller\ComprobanteController;
use Comprobante\Service\ComprobanteManager;
use Persona\Service\PersonaManager;

/**
 * Description of ComprobanteControllerFactory
 *
 */
class ComprobanteControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $comprobanteManager = $container->get(ComprobanteManager::class);
        $personaManager = $container->get(PersonaManager::class);

        // Instantiate the service and inject dependencies
        return new ComprobanteController($entityManager, $comprobanteManager, $personaManager);
    }
}
