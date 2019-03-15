<?php
namespace Bienes\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Bienes\Controller\BienesController;
use Bienes\Service\BienesManager;
use Producto\Service\ProductoManager;
use Servicio\Service\ServicioManager;
use Licencia\Service\LicenciaManager;
/**
 * Description of BienesControllerFactory
 *
 * @author mariano
 */
class BienesControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $bienesManager = $container->get(BienesManager::class);
        // Instantiate the service and inject dependencies
        return new BienesController($bienesManager);
    }    
}
