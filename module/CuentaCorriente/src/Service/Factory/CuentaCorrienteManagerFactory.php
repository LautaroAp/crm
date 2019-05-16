<?php
namespace CuentaCorriente\Service\Factory;

use Interop\Container\ContainerInterface;
use CuentaCorriente\Service\CuentaCorrienteManager;
use Persona\Service\PersonaManager;

/**
 * This is the factory class for CuentaCorrienteManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CuentaCorrienteManagerFactory
{
    /**
     * This method creates the CuentaCorrienteManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $personaManager = $container->get(PersonaManager::class);         
         
        return new CuentaCorrienteManager($entityManager, $personaManager);
    }
}
