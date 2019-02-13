<?php
namespace Evento\Service\Factory;

use Interop\Container\ContainerInterface;
use Evento\Service\EventoVentaManager;
use Persona\Service\PersonaManager;

/**
 * This is the factory class for EventoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class EventoVentaManagerFactory
{
    /**
     * This method creates the EventoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $personaManager = $container->get(PersonaManager::class);
        return new EventoVentaManager($entityManager, $personaManager);
    }
}
