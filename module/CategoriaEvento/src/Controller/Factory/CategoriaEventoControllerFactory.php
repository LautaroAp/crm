<?php
namespace CategoriaEvento\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use CategoriaEvento\Controller\CategoriaEventoController;
use CategoriaEvento\Service\CategoriaEventoManager;
use TipoEvento\Service\TipoEventoManager;

/**
 * Description of CategoriaEventoControllerFactory
 *
 * @author mariano
 */
class CategoriaEventoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categoriaEventoManager = $container->get(CategoriaEventoManager::class);
        $tipoEventoManager = $container->get(TipoEventoManager::class);
        // Instantiate the service and inject dependencies
        return new CategoriaEventoController($entityManager, $categoriaEventoManager, $tipoEventoManager);
    }    
}
