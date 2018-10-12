<?php
namespace Ejecutivo\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Ejecutivo\Controller\EjecutivoController;
use Ejecutivo\Service\EjecutivoManager;

/**
 * Description of EjecutivoControllerFactory
 *
 * @author mariano
 */
class EjecutivoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ejecutivoManager = $container->get(EjecutivoManager::class);
        
        // Instantiate the service and inject dependencies      
        return new EjecutivoController($entityManager, $ejecutivoManager);
    }    
}
