<?php

namespace Moneda\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Moneda\Controller\MonedaController;
use Moneda\Service\MonedaManager;
use Servicio\Service\ServicioManager;

/**
 * Description of MonedaControllerFactory
 *
 */
class MonedaControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $monedaManager = $container->get(MonedaManager::class);
        $servicioManager = $container->get(ServicioManager::class);

        // Instantiate the service and inject dependencies
        return new MonedaController($entityManager, $monedaManager, $servicioManager);
    }
}
