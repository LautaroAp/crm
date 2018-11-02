<?php
namespace TipoEvento\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use TipoEvento\Controller\TipoEventoController;
use TipoEvento\Service\TipoEventoManager;
use Evento\Service\EventoManager;

/**
 * Description of TipoEventoControllerFactory
 *
 * @author mariano
 */
class TipoEventoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $tipoeventoManager = $container->get(TipoEventoManager::class);
        $eventoManager = $container->get(EventoManager::class);
        // Instantiate the service and inject dependencies
        return new TipoEventoController($entityManager, $tipoeventoManager, $eventoManager);
    }    
}
