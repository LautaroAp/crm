<?php
namespace Persona\Service\Factory;

use Interop\Container\ContainerInterface;
use Persona\Service\PersonaManager;
use TipoFactura\Service\TipoFacturaManager;
/**
 * This is the factory class for PersonaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class PersonaManagerFactory
{
    /**
     * This method creates the PersonaManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $tipoFacturaManager = $container->get(TipoFacturaManager::class);   
        return new PersonaManager($entityManager, $tipoFacturaManager);
    }
}
