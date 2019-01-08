<?php

namespace Proveedor\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Proveedor\Controller\ProveedorInactivosController;
use Proveedor\Service\ProveedorManager;

class ProveedorInactivoControllerFactory implements FactoryInterface {
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $proveedorManager = $container->get(ProveedorManager::class);

        // Instantiate the service and inject dependencies
        return new ProveedorInactivoController($proveedorManager);
    }
}
