<?php
namespace Presupuesto\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Transaccion\Controller\TransaccionController;
use Transaccion\Service\TransaccionManager;

/**
 * Description of PresupuestoControllerFactory
 *
 * @author SoftHuella
 */
class PresupuestoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $transaccionManager = $container->get(TransaccionManager::class);

        // Instantiate the service and inject dependencies
        return new TransaccionController($transaccionManager);
    }    
}
