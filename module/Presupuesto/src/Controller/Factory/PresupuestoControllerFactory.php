<?php
namespace Presupuesto\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Presupuesto\Controller\PresupuestoController;
use Presupuesto\Service\PresupuestoManager;
use Persona\Service\PersonaManager;
use Moneda\Service\MonedaManager;
use Proveedor\Service\ProveedorManager;
use Clientes\Service\ClientesManager;
use BienesTransacciones\Service\BienesTransaccionesManager;
use Bienes\Service\BienesManager;
use FormaPago\Service\FormaPagoManager;
use FormaEnvio\Service\FormaEnvioManager;
use Iva\Service\IvaManager;


// use Transaccion\Service\TransaccionManager;

/**
 * Description of PresupuestoControllerFactory
 *
 * @author SoftHuella
 */
class PresupuestoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $presupuestoManager = $container->get(PresupuestoManager::class);
        $monedaManager = $container->get(MonedaManager::class);    
        $personaManager = $container->get(PersonaManager::class);            
        $clientesManager = $container->get(ClientesManager::class);            
        $proveedorManager = $container->get(ProveedorManager::class);
        $bienesTransaccionesManager = $container->get(BienesTransaccionesManager::class);
        $bienesManager = $container->get(BienesManager::class);
        $formaPagoManager = $container->get(FormaPagoManager::class);
        $formaEnvioManager = $container->get(FormaEnvioManager::class);
        $ivaManager = $container->get(IvaManager::class);

        // Instantiate the service and inject dependencies
        return new PresupuestoController($presupuestoManager, $monedaManager, $personaManager, $clientesManager, $proveedorManager,$bienesTransaccionesManager, $bienesManager, $formaPagoManager, $formaEnvioManager, $ivaManager);
    }    
}