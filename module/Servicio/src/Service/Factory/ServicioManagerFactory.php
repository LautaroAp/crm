<?php
namespace Servicio\Service\Factory;

use Interop\Container\ContainerInterface;
use Servicio\Service\ServicioManager;
use Iva\Service\IvaManager;
use Categoria\Service\CategoriaManager;
use Proveedor\Service\ProveedoraManager;


/**
 * This is the factory class for ServicioManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ServicioManagerFactory
{
    /**
     * This method creates the ServicioManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ivaManager = $container->get(IvaManager::class);   
        $categoriaManager = $container->get(CategoriaManager::class);         
        $proveedorManager = $container->get(ProveedorManager::class);         
        return new ServicioManager($entityManager, $ivaManager, $categoriaManager, $proveedorManager);
    }
}
