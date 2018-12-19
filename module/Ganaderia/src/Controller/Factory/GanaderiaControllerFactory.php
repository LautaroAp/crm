<?php

namespace Ganaderia\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Ganaderia\Controller\GanaderiaController;
use Ganaderia\Service\GanaderiaManager;

/**
 * Description of GanaderiaControllerFactory
 *
 * @author mariano
 */
class GanaderiaControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ganaderiaManager = $container->get(GanaderiaManager::class);
        // Instantiate the service and inject dependencies
        return new GanaderiaController($entityManager, $ganaderiaManager);
    }
}
