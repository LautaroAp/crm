<?php

namespace TipoFactura\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use TipoFactura\Controller\TipoFacturaController;
use TipoFactura\Service\TipoFacturaManager;
use Licencia\Service\LicenciaManager;
use Producto\Service\ProductoManager;
use Servicio\Service\ServicioManager;

/**
 * Description of TipoFacturaControllerFactory
 *
 */
class TipoFacturaControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $tipoFacturaManager = $container->get(TipoFacturaManager::class);
        // Instantiate the service and inject dependencies
        return new TipoFacturaController($entityManager, $tipoFacturaManager);
    }
}
