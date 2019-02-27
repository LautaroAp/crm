<?php
namespace Producto\Service\Factory;

use Interop\Container\ContainerInterface;
use Producto\Service\ProductoManager;
use Iva\Service\IvaManager;
use Categoria\Service\CategoriaManager;
use Proveedor\Service\ProveedorManager;
use Bienes\Service\BienesManager;

/**
 * This is the factory class for ProductoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ProductoManagerFactory
{
    /**
     * This method creates the ProductoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ivaManager = $container->get(IvaManager::class);
        $categoriaManager = $container->get(CategoriaManager::class);         
        $proveedorManager = $container->get(ProveedorManager::class);
        $bienesManager = $container->get(BienesManager::class);

        return new ProductoManager($entityManager, $ivaManager, $categoriaManager, $proveedorManager,
        $bienesManager);
    }
}
