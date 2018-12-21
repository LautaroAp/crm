<?php
namespace CategoriaLicencia\Service\Factory;

use Interop\Container\ContainerInterface;
use CategoriaLicencia\Service\CategoriaLicenciaManager;

/**
 * This is the factory class for CategoriaLicenciaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CategoriaLicenciaManagerFactory
{
    /**
     * This method creates the CategoriaLicenciaManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');                       
        return new CategoriaLicenciaManager($entityManager);
    }
}
