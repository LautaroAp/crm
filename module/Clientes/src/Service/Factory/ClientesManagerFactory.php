<?php

namespace Clientes\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Clientes\Service\ClientesManager;

class ClientesManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Instantiate the service and inject dependencies
        return new ClientesManager($entityManager);
    }

}
