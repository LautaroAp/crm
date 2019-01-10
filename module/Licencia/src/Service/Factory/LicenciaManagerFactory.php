<?php
namespace Licencia\Service\Factory;

use Interop\Container\ContainerInterface;
use Licencia\Service\LicenciaManager;
use Iva\Service\IvaManager;
use Categoria\Service\CategoriaManager;
use Proveedor\Service\ProveedorManager;

/**
 * This is the factory class for LicenciaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class LicenciaManagerFactory
{
    /**
     * This method creates the LicenciaManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ivaManager = $container->get(IvaManager::class);
        $categoriaManager = $container->get(CategoriaManager::class);
        $proveedorManager = $container->get(ProveedorManager::class);

        return new LicenciaManager($entityManager, $ivaManager, $categoriaManager, $proveedorManager);
    }
}
