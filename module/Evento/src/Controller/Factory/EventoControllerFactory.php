<?php
namespace Evento\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Evento\Controller\EventoController;
use Evento\Service\EventoManager;

/**
 * Description of EventoControllerFactory
 *
 * @author mariano
 */
class EventoControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $eventoManager = $container->get(EventoManager::class);
        // Instantiate the service and inject dependencies
        return new EventoController($entityManager, $eventoManager);
    }    
}
