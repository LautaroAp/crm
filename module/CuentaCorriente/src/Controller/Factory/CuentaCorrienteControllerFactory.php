<?php
namespace CuentaCorriente\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use CuentaCorriente\Controller\CuentaCorrienteController;
use CuentaCorriente\Service\CuentaCorrienteManager;
use Iva\Service\IvaManager;
/**
 * Description of CuentaCorrienteControllerFactory
 *
 * @author mariano
 */
class CuentaCorrienteControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $cuentaCorrienteManager = $container->get(CuentaCorrienteManager::class);
        $ivaManager = $container->get(IvaManager::class);
        // Instantiate the service and inject dependencies
        return new CuentaCorrienteController($cuentaCorrienteManager, $ivaManager);
    }    
}
