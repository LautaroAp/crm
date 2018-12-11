<?php

namespace Clientes\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Clientes\Controller\ClientesInactivosController;
use Clientes\Service\ClientesManager;

class ClientesInactivosControllerFactory implements FactoryInterface {
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $clientesManager = $container->get(ClientesManager::class);

        // Instantiate the service and inject dependencies
        return new ClientesInactivosController($clientesManager);
    }
}
