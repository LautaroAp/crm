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

// use Transaccion\Service\TransaccionManager;

/**
 * Description of PedidoControllerFactory
 *
 * @author SoftHuella
 */
class PresupuestoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $pedidoManager = $container->get(PresupuestoManager::class);
        $monedaManager = $container->get(MonedaManager::class);    
        $personaManager = $container->get(PersonaManager::class);            
        $clientesManager = $container->get(ClientesManager::class);            
        $proveedorManager = $container->get(ProveedorManager::class);            
        $bienesTransaccionesManager = $container->get(BienesTransaccionesManager::class);
        // $transaccionManager = $container->get(TransaccionManager::class);            


        // Instantiate the service and inject dependencies
        return new PresupuestoController($pedidoManager, $monedaManager, $personaManager, $clientesManager, 
        $proveedorManager, $bienesTransaccionesManager);
    }    
}