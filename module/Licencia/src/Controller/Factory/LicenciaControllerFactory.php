<?php

namespace Licencia\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Licencia\Controller\LicenciaController;
use Licencia\Service\LicenciaManager;
use Clientes\Service\ClientesManager;
use Iva\Service\IvaManager;

/**
 * Description of LicenciaControllerFactory
 *
 * @author mariano
 */
class LicenciaControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $licenciaManager = $container->get(LicenciaManager::class);
        $clientesManager = $container->get(ClientesManager::class);
        $ivaManager = $container->get(IvaManager::class); 
        // Instantiate the service and inject dependencies
        return new LicenciaController($entityManager, $licenciaManager, $clientesManager, $ivaManager);
    }

}
