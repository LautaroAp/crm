<?php
namespace Transaccion\Service\Factory;

use Interop\Container\ContainerInterface;
use Transaccion\Service\TransaccionManager;
use Persona\Service\PersonaManager;


/**
 * This is the factory class for TransaccionManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class TransaccionManagerFactory
{
    /**
     * This method creates the TransaccionManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');        
        $personaManager = $container->get(PersonaManager::class);         
        return new TransaccionManager($entityManager,$personaManager);
    }
}
