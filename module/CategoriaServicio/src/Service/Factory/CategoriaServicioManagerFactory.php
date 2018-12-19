<?php
namespace CategoriaServicio\Service\Factory;

use Interop\Container\ContainerInterface;
use CategoriaServicio\Service\CategoriaServicioManager;

/**
 * This is the factory class for CategoriaServicioManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CategoriaServicioManagerFactory
{
    /**
     * This method creates the CategoriaServicioManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');                       
        return new CategoriaServicioManager($entityManager);
    }
}
