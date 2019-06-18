<?php
namespace Servicio\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Servicio\Controller\ServicioController;
use Servicio\Service\ServicioManager;
use Iva\Service\IvaManager;
use Categoria\Service\CategoriaManager;
use Bienes\Service\BienesManager;
use Clientes\Service\ClientesManager;

/**
 * Description of ServicioControllerFactory
 *
 * @author SoftHuella
 */
class ServicioControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $servicioManager = $container->get(ServicioManager::class);
        $ivaManager = $container->get(IvaManager::class);            
        $categoriaManager = $container->get(CategoriaManager::class);
        $bienesManager = $container->get(BienesManager::class);
        $clientesManager = $container->get(ClientesManager::class);

        // Instantiate the service and inject dependencies
        return new ServicioController($entityManager, $servicioManager, $ivaManager,
        $categoriaManager, $bienesManager, $clientesManager);
    }    
}
