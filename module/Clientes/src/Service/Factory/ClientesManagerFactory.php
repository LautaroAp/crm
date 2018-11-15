<?php

namespace Clientes\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Clientes\Service\ClientesManager;
use Usuario\Service\UsuarioManager;

class ClientesManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $usuarioManager = $container->get(UsuarioManager::class);

        return new ClientesManager($entityManager, $usuarioManager);
    }

}
