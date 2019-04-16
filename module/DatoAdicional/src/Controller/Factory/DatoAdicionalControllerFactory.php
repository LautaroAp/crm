<?php
namespace DatoAdicional\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use DatoAdicional\Controller\DatoAdicionalController;
use DatoAdicional\Service\DatoAdicionalManager;
use Persona\Service\PersonaManager;

/**
 * Description of DatoAdicionalControllerFactory
 *
 * @author mariano
 */
class DatoAdicionalControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $datoAdicionalManager = $container->get(DatoAdicionalManager::class);
        $personaManager = $container->get(PersonaManager::class);
        // Instantiate the service and inject dependencies
        return new DatoAdicionalController($datoAdicionalManager, $personaManager);
    }    
}
