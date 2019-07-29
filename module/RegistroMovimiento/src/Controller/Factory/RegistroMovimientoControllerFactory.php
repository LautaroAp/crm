<?php

namespace RegistroMovimiento\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use RegistroMovimiento\Controller\RegistroMovimientoController;
use RegistroMovimiento\Service\RegistroMovimientoManager;
use Servicio\Service\ServicioManager;
/**
 * Description of RegistroMovimientoControllerFactory
 *
 */
class RegistroMovimientoControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $registroMovimientoManager = $container->get(RegistroMovimientoManager::class);
        $servicioManager = $container->get(ServicioManager::class);

        // Instantiate the service and inject dependencies
        return new RegistroMovimientoController($entityManager, $registroMovimientoManager, $servicioManager);
    }
}
