<?php
namespace Factura\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Factura\Controller\FacturaController;
use Factura\Service\FacturaManager;
use Persona\Service\PersonaManager;
use Moneda\Service\MonedaManager;
use Proveedor\Service\ProveedorManager;
use Clientes\Service\ClientesManager;
use BienesTransacciones\Service\BienesTransaccionesManager;
use Bienes\Service\BienesManager;
use FormaPago\Service\FormaPagoManager;
use FormaEnvio\Service\FormaEnvioManager;
use Iva\Service\IvaManager;
use Empresa\Service\EmpresaManager;
use TipoFactura\Service\TipoFacturaManager;

// use Transaccion\Service\TransaccionManager;

/**
 * Description of FacturaControllerFactory
 *
 * @author SoftHuella
 */
class FacturaControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $facturaManager = $container->get(FacturaManager::class);
        $monedaManager = $container->get(MonedaManager::class);    
        $personaManager = $container->get(PersonaManager::class);            
        $clientesManager = $container->get(ClientesManager::class);            
        $proveedorManager = $container->get(ProveedorManager::class);
        $bienesTransaccionesManager = $container->get(BienesTransaccionesManager::class);
        $bienesManager = $container->get(BienesManager::class);
        $formaPagoManager = $container->get(FormaPagoManager::class);
        $formaEnvioManager = $container->get(FormaEnvioManager::class);
        $ivaManager = $container->get(IvaManager::class);
        $empresaManager= $container->get(EmpresaManager::class);
        $tipoFacturaManager = $container->get(TipoFacturaManager::class);

        // $transaccionManager = $container->get(TransaccionManager::class);            


        // Instantiate the service and inject dependencies
        return new FacturaController($facturaManager, $monedaManager, $personaManager, $clientesManager, 
        $proveedorManager,$bienesTransaccionesManager, $bienesManager, $formaPagoManager, $formaEnvioManager, $ivaManager, $empresaManager, $tipoFacturaManager);
    }    
}
