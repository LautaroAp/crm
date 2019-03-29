<?php
namespace FormaPago\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use FormaPago\Controller\FormaPagoController;
use FormaPago\Service\FormaPagoManager;

/**
 * Description of FormaPagoControllerFactory
 *
 * @author mariano
 */
class FormaPagoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $formaPagoManager = $container->get(FormaPagoManager::class);
        // Instantiate the service and inject dependencies
        return new FormaPagoController($formaPagoManager);
    }    
}
