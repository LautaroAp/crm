<?php

namespace Iva\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Iva\Controller\IvaController;
use Iva\Service\IvaManager;
use Licencia\Service\LicenciaManager;
use Producto\Service\ProductoManager;
use Servicio\Service\ServicioManager;

/**
 * Description of IvaControllerFactory
 *
 */
class IvaControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ivaManager = $container->get(IvaManager::class);
        $licenciaManager = $container->get(LicenciaManager::class);
        $productoManager = $container->get(ProductoManager::class);
        $servicioManager = $container->get(ServicioManager::class);

        // Instantiate the service and inject dependencies
        return new IvaController($entityManager, $ivaManager,$licenciaManager, $productoManager, $servicioManager);
    }
}
