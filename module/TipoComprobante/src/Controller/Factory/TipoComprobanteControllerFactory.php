<?php

namespace TipoComprobante\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use TipoComprobante\Controller\TipoComprobanteController;
use TipoComprobante\Service\TipoComprobanteManager;
use Comprobante\Service\ComprobanteManager;

/**
 * Description of TipoComprobanteControllerFactory
 *
 */
class TipoComprobanteControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $tipoComprobanteManager = $container->get(TipoComprobanteManager::class);
        $comprobanteManager = $container->get(ComprobanteManager::class);

        // Instantiate the service and inject dependencies
        return new TipoComprobanteController($entityManager, $tipoComprobanteManager, $comprobanteManager);
    }
}
