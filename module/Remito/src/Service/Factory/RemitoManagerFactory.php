<?php
namespace Remito\Service\Factory;

use Interop\Container\ContainerInterface;
use Remito\Service\RemitoManager;
use Moneda\Service\MonedaManager;
use Transaccion\Service\TransaccionManager;
use BienesTransacciones\Service\BienesTransaccionesManager;
use Persona\Service\PersonaManager;
use Iva\Service\IvaManager;
use FormaPago\Service\FormaPagoManager;
use FormaEnvio\Service\FormaEnvioManager;

/**
 * This is the factory class for RemitoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class RemitoManagerFactory
{
    /**
     * This method creates the RemitoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $monedaManager = $container->get(MonedaManager::class);   
        $personaManager = $container->get(PersonaManager::class);
        $bienesTransaccionesManager = $container->get(BienesTransaccionesManager::class);  
        $ivaManager = $container->get(IvaManager::class); 
        $formaPagoManager = $container->get(FormaPagoManager::class); 
        $formaEnvioManager = $container->get(FormaEnvioManager::class); 

        return new RemitoManager($entityManager, $monedaManager,$personaManager, $bienesTransaccionesManager, $ivaManager, $formaPagoManager, $formaEnvioManager);
    }
}
