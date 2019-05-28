<?php
namespace NotaCredito\Service\Factory;

use Interop\Container\ContainerInterface;
use NotaCredito\Service\NotaCreditoManager;
use Moneda\Service\MonedaManager;
use Transaccion\Service\TransaccionManager;
use BienesTransacciones\Service\BienesTransaccionesManager;
use Bienes\Service\BienesManager;
use Persona\Service\PersonaManager;
use Iva\Service\IvaManager;
use FormaPago\Service\FormaPagoManager;
use FormaEnvio\Service\FormaEnvioManager;
use CuentaCorriente\Service\CuentaCorrienteManager;
/**
 * This is the factory class for NotaCreditoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class NotaCreditoManagerFactory
{
    /**
     * This method creates the NotaCreditoManager service and returns its instance. 
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
        $bienesManager = $container->get(BienesManager::class); 
        $cuentaCorrienteManager = $container->get(CuentaCorrienteManager::class);   
        return new NotaCreditoManager($entityManager, $monedaManager,$personaManager, $bienesTransaccionesManager, $ivaManager, $formaPagoManager,$formaEnvioManager, $bienesManager, $cuentaCorrienteManager);
    }
}
