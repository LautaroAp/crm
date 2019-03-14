<?php
namespace Pedido\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Pedido\Controller\PedidoController;
use Pedido\Service\PedidoManager;
use Moneda\Service\MonedaManager;
// use Transaccion\Service\TransaccionManager;

/**
 * Description of PedidoControllerFactory
 *
 * @author SoftHuella
 */
class PedidoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $pedidoManager = $container->get(PedidoManager::class);
        $monedaManager = $container->get(MonedaManager::class);            
        // $transaccionManager = $container->get(TransaccionManager::class);            


        // Instantiate the service and inject dependencies
        return new PedidoController($entityManager, $pedidoManager, $monedaManager);
    }    
}
