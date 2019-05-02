<?php
namespace Transaccion\Service\Factory;

use Interop\Container\ContainerInterface;
use Transaccion\Service\TransaccionManager;
use Persona\Service\PersonaManager;
use BienesTransacciones\Service\BienesTransaccionesManager;
use Iva\Service\IvaManager;
use FormaPago\Service\FormaPagoManager;
use FormaEnvio\Service\FormaEnvioManager;
use Moneda\Service\MonedaManager;
use Empresa\Service\EmpresaManager;

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
        $formaPagoManager= $container->get(FormaPagoManager::class);
        $formaEnvioManager= $container->get(FormaEnvioManager::class);
        $monedaManager= $container->get(MonedaManager::class);
        // $empresaManager= $container->get(EmpresaManager::class);


        return new TransaccionManager($entityManager,$personaManager, $bienesTransaccionesManager, $ivaManager,
    $formaPagoManager, $formaEnvioManager, $monedaManager/*, $empresaManager*/);
    }
}
