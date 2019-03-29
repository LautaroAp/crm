<?php
namespace FormaPago\Service\Factory;

use Interop\Container\ContainerInterface;
use FormaPago\Service\FormaPagoManager;

/**
 * This is the factory class for FormaPagoManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class FormaPagoManagerFactory
{
    /**
     * This method creates the FormaPagoManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new FormaPagoManager($entityManager);
    }
}
