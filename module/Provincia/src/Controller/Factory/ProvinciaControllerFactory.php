<?php

namespace Provincia\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Provincia\Controller\ProvinciaController;
use Provincia\Service\ProvinciaManager;

/**
 * Esta clase se encarga de crear una instancia de ProvinciaController
 *
 * @author SoftHuella
 */
class ProvinciaControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $provinciaManager = $container->get(ProvinciaManager::class);
        // Instantiate the service and inject dependencies
        return new ProvinciaController($entityManager, $provinciaManager);
    }

}
