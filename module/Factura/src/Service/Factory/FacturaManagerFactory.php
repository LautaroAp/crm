<?php
namespace Factura\Service\Factory;

use Interop\Container\ContainerInterface;
use Factura\Service\FacturaManager;
use Moneda\Service\MonedaManager;
use Transaccion\Service\TransaccionManager;
use BienesTransacciones\Service\BienesTransaccionesManager;
use Bienes\Service\BienesManager;
use Persona\Service\PersonaManager;
use Iva\Service\IvaManager;
use FormaPago\Service\FormaPagoManager;
use FormaEnvio\Service\FormaEnvioManager;
use CuentaCorriente\Service\CuentaCorrienteManager;
use TipoFactura\Service\TipoFacturaManager;
/**
 * This is the factory class for FacturaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class FacturaManagerFactory
{
    /**
     * This method creates the FacturaManager service and returns its instance. 
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
        $tipoFacturaManager = $container->get(TipoFacturaManager::class);   
   
        return new FacturaManager($entityManager, $monedaManager,$personaManager, $bienesTransaccionesManager, $ivaManager, $formaPagoManager,$formaEnvioManager, $bienesManager, $cuentaCorrienteManager, $tipoFacturaManager);
    }
}
