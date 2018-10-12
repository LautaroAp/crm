<?php
namespace Pais\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Pais\Controller\PaisController;
use Pais\Service\PaisManager;

/**
 * Description of PaisControllerFactory
 *
 * @author mariano
 */
class PaisControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $paisManager = $container->get(PaisManager::class);
        // Instantiate the service and inject dependencies
        return new PaisController($entityManager, $paisManager);
    }    
}
