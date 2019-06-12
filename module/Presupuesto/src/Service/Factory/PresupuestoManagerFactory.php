<?php
namespace Presupuesto\Service\Factory;

use Interop\Container\ContainerInterface;
use Presupuesto\Service\PresupuestoManager;
use Moneda\Service\MonedaManager;
use Transaccion\Service\TransaccionManager;
use BienesTransacciones\Service\BienesTransaccionesManager;
use Persona\Service\PersonaManager;
use Iva\Service\IvaManager;
use FormaPago\Service\FormaPagoManager;
use FormaEnvio\Service\FormaEnvioManager;
use Bienes\Service\BienesManager;
use CuentaCorriente\Service\CuentaCorrienteManager;
use Comprobante\Service\ComprobanteManager;
/**
 * This is the factory class for PresupuestoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class PresupuestoManagerFactory
{
    /**
     * This method creates the PresupuestoManager service and returns its instance. 
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
        $comprobanteManager = $container->get(ComprobanteManager::class);  

        return new PresupuestoManager($entityManager, $monedaManager,$personaManager, $bienesTransaccionesManager, $ivaManager, $formaPagoManager, $formaEnvioManager, $bienesManager, $cuentaCorrienteManager, $comprobanteManager);
    }
}
