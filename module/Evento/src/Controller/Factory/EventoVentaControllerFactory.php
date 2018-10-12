<?php
namespace Evento\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Evento\Controller\EventoVentaController;
use Evento\Service\EventoVentaManager;

/**
 * Description of EventoControllerFactory
 *
 * @author mariano
 */
class EventoVentaControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $eventoVentaManager = $container->get(EventoVentaManager::class);
        // Instantiate the service and inject dependencies
        return new EventoVentaController($entityManager, $eventoVentaManager);
    }    
}
