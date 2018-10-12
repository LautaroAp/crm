<?php
namespace CategoriaCliente\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use CategoriaCliente\Controller\CategoriaClienteController;
use CategoriaCliente\Service\CategoriaClienteManager;

/**
 * Description of CategoriaClienteControllerFactory
 *
 * @author mariano
 */
class CategoriaClienteControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categoriaclienteManager = $container->get(CategoriaClienteManager::class);
        // Instantiate the service and inject dependencies
        return new CategoriaClienteController($entityManager, $categoriaclienteManager);
    }    
}
