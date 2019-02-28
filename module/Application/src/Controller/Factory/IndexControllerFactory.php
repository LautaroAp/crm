<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\IndexController;
use Empresa\Service\EmpresaManager;

/**
 * Description of LocalidadControllerFactory
 *
 * @author mariano
 */
class IndexControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $empresaManager = $container->get(EmpresaManager::class);

        return new IndexController($entityManager, $empresaManager);
    }    
}
