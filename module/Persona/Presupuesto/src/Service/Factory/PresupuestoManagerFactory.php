<?php
namespace Servicio\Service\Factory;

use Interop\Container\ContainerInterface;
use Servicio\Service\ServicioManager;
use Moneda\Service\MonedaManager;
use Transaccion\Service\TransaccionManager;


/**
 * This is the factory class for ServicioManager service. The purpose of the factory
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
        $transaccionManager = $container->get(TransaccionManager::class);   

        return new PresupuestoManager($entityManager, $monedaManager, $transaccionManager);
    }
}
