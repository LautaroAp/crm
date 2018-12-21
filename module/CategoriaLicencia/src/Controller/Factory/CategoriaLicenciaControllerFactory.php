<?php

namespace CategoriaLicencia\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use CategoriaLicencia\Controller\CategoriaLicenciaController;
use CategoriaLicencia\Service\CategoriaLicenciaManager;
use Licencia\Service\LicenciaManager;

/**
 * Description of CategoriaLicenciaControllerFactory
 *
 * @author mariano
 */
class CategoriaLicenciaControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categoriaLicenciaManager = $container->get(CategoriaLicenciaManager::class);
        $licenciaManager = $container->get(LicenciaManager::class);

        // Instantiate the service and inject dependencies
        return new CategoriaLicenciaController($entityManager, $categoriaLicenciaManager, $licenciaManager);
    }
}
