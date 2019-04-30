<?php
namespace Clientes\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Clientes\Controller\ClientesController;
use Clientes\Service\ClientesManager;
use TipoEvento\Service\TipoEventoManager;
use Evento\Service\EventoManager;
use Persona\Service\PersonaManager;
use DatoAdicional\Service\DatoAdicionalManager;
use Servicio\Service\ServicioManager;

class ClientesControllerFactory implements FactoryInterface {
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $clientesManager = $container->get(ClientesManager::class);
        $tipoEventosManager = $container->get(TipoEventoManager::class);
        $eventoManager = $container->get(EventoManager::class);
        $personaManager = $container->get(PersonaManager::class);
        $datoAdicionalManager = $container->get(DatoAdicionalManager::class);
        $servicioManager = $container->get(ServicioManager::class);

        return new ClientesController($clientesManager, $tipoEventosManager,
         $eventoManager, $personaManager, $datoAdicionalManager, $servicioManager);
    }    
}
