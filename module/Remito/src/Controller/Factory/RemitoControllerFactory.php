<?php
namespace Remito\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Remito\Controller\RemitoController;
use Remito\Service\RemitoManager;
use Moneda\Service\MonedaManager;
use Transaccion\Service\TransaccionManager;

/**
 * Description of RemitoControllerFactory
 *
 * @author SoftHuella
 */
class RemitoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $remitoManager = $container->get(RemitoManager::class);
        $monedaManager = $container->get(MonedaManager::class);            
        $transaccionManager = $container->get(TransaccionManager::class);            


        // Instantiate the service and inject dependencies
        return new RemitoController($entityManager, $remitoManager, $monedaManager, $transaccionManager);
    }    
}
