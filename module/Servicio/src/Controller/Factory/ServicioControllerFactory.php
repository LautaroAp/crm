<?php
namespace Servicio\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Servicio\Controller\ServicioController;
use Servicio\Service\ServicioManager;

/**
 * Description of ServicioControllerFactory
 *
 * @author mariano
 */
class ServicioControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $servicioManager = $container->get(ServicioManager::class);
        // Instantiate the service and inject dependencies
        return new ServicioController($entityManager, $servicioManager);
    }    
}
