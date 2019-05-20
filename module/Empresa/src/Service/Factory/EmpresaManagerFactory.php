<?php
namespace Empresa\Service\Factory;

use Interop\Container\ContainerInterface;
use Empresa\Service\EmpresaManager;
use Moneda\Service\MonedaManager;
/**
 * This is the factory class for EmpresaManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class EmpresaManagerFactory
{
    /**
     * This method creates the EmpresaManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $monedaManager = $container->get(MonedaManager::class);
        return new EmpresaManager($entityManager,$monedaManager);
    }
}
