<?php

namespace Banco\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Banco\Controller\BancoController;
use Banco\Service\BancoManager;
use Servicio\Service\ServicioManager;
/**
 * Description of BancoControllerFactory
 *
 */
class BancoControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $bancoManager = $container->get(BancoManager::class);
        $servicioManager = $container->get(ServicioManager::class);

        // Instantiate the service and inject dependencies
        return new BancoController($entityManager, $bancoManager, $servicioManager);
    }
}
