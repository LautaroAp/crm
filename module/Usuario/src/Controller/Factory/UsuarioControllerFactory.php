<?php
namespace Usuario\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Usuario\Controller\UsuarioController;
use Usuario\Service\UsuarioManager;

/**
 * Esta clase crea una instancia de UsuarioController 
 *
 * @author SoftHuella 
 */
class UsuarioControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ejecutivoManager = $container->get(UsuarioManager::class);
        
        // Instantiate the service and inject dependencies      
        return new UsuarioController($entityManager, $ejecutivoManager);
    }    
}
