<?php
namespace Transaccion\Service\Factory;

use Interop\Container\ContainerInterface;
use Transaccion\Service\TransaccionManager;
use Persona\Service\PersonaManager;
use BienesTransacciones\Service\BienesTransaccionesManager;
use Iva\Service\IvaManager;


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
        $bienesTransaccionesManager = $container->get(BienesTransaccionesManager::class);   
        $ivaManager= $container->get(IvaManager::class);
        return new TransaccionManager($entityManager,$personaManager, $bienesTransaccionesManager, $ivaManager);
    }
}
