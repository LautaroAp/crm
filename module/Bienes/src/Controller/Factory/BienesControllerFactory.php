<?php
namespace Bienes\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Bienes\Controller\BienesController;
use Bienes\Service\BienesManager;
use Iva\Service\IvaManager;
/**
 * Description of BienesControllerFactory
 *
 * @author mariano
 */
class BienesControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $bienesManager = $container->get(BienesManager::class);
        $ivaManager = $container->get(IvaManager::class);
        // Instantiate the service and inject dependencies
        return new BienesController($bienesManager, $ivaManager);
    }    
}
