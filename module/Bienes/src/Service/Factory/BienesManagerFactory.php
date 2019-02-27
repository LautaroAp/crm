<?php
namespace Bienes\Service\Factory;

use Interop\Container\ContainerInterface;
use Bienes\Service\BienesManager;
use Iva\Service\IvaManager;
use Categoria\Service\CategoriaManager;
use Proveedor\Service\ProveedorManager;


/**
 * This is the factory class for BienesManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class BienesManagerFactory
{
    /**
     * This method creates the BienesManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ivaManager = $container->get(IvaManager::class);   
        $categoriaManager = $container->get(CategoriaManager::class);         
        $proveedorManager = $container->get(ProveedorManager::class);         
        return new BienesManager($entityManager, $ivaManager, $categoriaManager, $proveedorManager);
    }
}
