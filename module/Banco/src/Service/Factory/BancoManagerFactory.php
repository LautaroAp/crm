<?php
namespace Banco\Service\Factory;

use Interop\Container\ContainerInterface;
use Banco\Service\BancoManager;

/**
 * This is the factory class for BancoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class BancoManagerFactory
{
    /**
     * This method creates the BancoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');                        
        return new BancoManager($entityManager);
    }
}
