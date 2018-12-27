<?php
namespace Categoria\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Categoria\Controller\CategoriaController;
use Categoria\Service\CategoriaManager;
use TipoEvento\Service\TipoEventoManager;
use Clientes\Service\ClientesManager;
use Servicio\Service\ServicioManager;
use Producto\Service\ProductoManager;
use Licencia\Service\LicenciaManager;

/**
 * Description of CategoriaControllerFactory
 *
 * @author mariano
 */
class CategoriaControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categoriaEventoManager = $container->get(CategoriaManager::class);
        $tipoEventoManager = $container->get(TipoEventoManager::class);
        $clientesManager = $container->get(ClientesManager::class);
        $servicioManager = $container->get(ServicioManager::class);
        $productoManager = $container->get(ProductoManager::class);
        $licenciaManager = $container->get(LicenciaManager::class);

        // Instantiate the service and inject dependencies
        return new CategoriaController($entityManager, $categoriaEventoManager, $tipoEventoManager, 
        $clientesManager, $productoManager, $servicioManager, $licenciaManager);
    }    
}
