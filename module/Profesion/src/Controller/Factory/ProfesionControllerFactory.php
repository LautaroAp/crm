<?php
namespace Profesion\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Profesion\Controller\ProfesionController;
use Profesion\Service\ProfesionManager;
use Clientes\Service\ClientesManager;

/**
 * Description of ProfesionControllerFactory
 *
 * @author mariano
 */
class ProfesionControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $profesionManager = $container->get(ProfesionManager::class);
        $clientesManager = $container->get(ClientesManager::class);
        // Instantiate the service and inject dependencies
        return new ProfesionController($entityManager, $profesionManager, $clientesManager);
    }    
}
