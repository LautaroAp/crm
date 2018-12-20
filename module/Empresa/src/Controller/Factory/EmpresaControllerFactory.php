<?php

namespace Empresa\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Empresa\Controller\EmpresaController;
use Empresa\Service\EmpresaManager;
use Moneda\Service\MonedaManager;

/**
 * Description of EmpresaControllerFactory
 *
 * @author mariano
 */
class EmpresaControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $empresaManager = $container->get(EmpresaManager::class);
        $monedaManager = $container->get(MonedaManager::class);
        // Instantiate the service and inject dependencies
        return new EmpresaController($entityManager, $empresaManager, $monedaManager);
    }

}
