<?php

namespace CategoriaProducto\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use CategoriaProducto\Controller\CategoriaProductoController;
use CategoriaProducto\Service\CategoriaProductoManager;
use Producto\Service\ProductoManager;

/**
 * Description of CategoriaProductoControllerFactory
 *
 * @author mariano
 */
class CategoriaProductoControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categoriaProductoManager = $container->get(CategoriaProductoManager::class);
        $productoManager = $container->get(ProductoManager::class);
        // Instantiate the service and inject dependencies
        return new CategoriaProductoController($entityManager, $categoriaProductoManager, $productoManager);
    }
}
