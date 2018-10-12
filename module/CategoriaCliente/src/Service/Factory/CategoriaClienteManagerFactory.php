<?php
namespace CategoriaCliente\Service\Factory;

use Interop\Container\ContainerInterface;
use CategoriaCliente\Service\CategoriaClienteManager;

/**
 * This is the factory class for CategoriaClienteManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CategoriaClienteManagerFactory
{
    /**
     * This method creates the CategoriaClienteManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');
                        
        return new CategoriaClienteManager($entityManager, $viewRenderer, $config);
    }
}
