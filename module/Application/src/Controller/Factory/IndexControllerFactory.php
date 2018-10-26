<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\IndexController;
use Clientes\Service\ClientesManager;


/**
 * Description of LocalidadControllerFactory
 *
 * @author mariano
 */
class IndexControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $clientesManager = $container->get(ClientesManager::class);

       
        // Instantiate the service and inject dependencies
        return new IndexController($entityManager,$clientesManager);
    }    
}
