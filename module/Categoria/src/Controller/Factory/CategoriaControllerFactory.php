<?php
namespace Categoria\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Categoria\Controller\CategoriaController;
use Categoria\Service\CategoriaManager;
use TipoEvento\Service\TipoEventoManager;

/**
 * Description of CategoriaControllerFactory
 *
 * @author mariano
 */
class CategoriaControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categoriaEventoManager = $container->get(CategoriaManager::class);
        $tipoEventoManager = $container->get(TipoEventoManager::class);
        // Instantiate the service and inject dependencies
        return new CategoriaController($entityManager, $categoriaEventoManager, $tipoEventoManager);
    }    
}
