<?php
namespace Evento\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Evento\Controller\EventoController;
use Evento\Service\EventoManager;
use TipoEvento\Service\TipoEventoManager;
use Clientes\Service\ClientesManager;
use Proveedor\Service\ProveedorManager;

/**
 * Description of EventoControllerFactory
 *
 * @author mariano
 */
class EventoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $eventoManager = $container->get(EventoManager::class);
        $clienteManager = $container->get(ClientesManager::class);
        $proveedorManager = $container->get(ProveedorManager::class);
        $tipoEventoManager = $container->get(TipoEventoManager::class);
        // Instantiate the service and inject dependencies
        return new EventoController($entityManager, $eventoManager, $clienteManager, $proveedorManager,
         $tipoEventoManager);
    }    
}
