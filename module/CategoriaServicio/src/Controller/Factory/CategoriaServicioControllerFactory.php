<?php

namespace CategoriaServicio\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use CategoriaServicio\Controller\CategoriaServicioController;
use CategoriaServicio\Service\CategoriaServicioManager;
use Producto\Service\ProductoManager;

/**
 * Description of CategoriaServicioControllerFactory
 *
 * @author mariano
 */
class CategoriaServicioControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categoriaServicioManager = $container->get(CategoriaServicioManager::class);
        // Instantiate the service and inject dependencies
        return new CategoriaServicioController($entityManager, $categoriaServicioManager);
    }
}
