<?php
namespace FormaEnvio\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use FormaEnvio\Controller\FormaEnvioController;
use FormaEnvio\Service\FormaEnvioManager;
use Transaccion\Service\TransaccionManager;

/**
 * Description of FormaEnvioControllerFactory
 *
 * @author mariano
 */
class FormaEnvioControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formaEnvioManager = $container->get(FormaEnvioManager::class);
        $transaccionManager = $container->get(TransaccionManager::class);
        // Instantiate the service and inject dependencies
        return new FormaEnvioController($formaEnvioManager, $transaccionManager);
    }    
}
