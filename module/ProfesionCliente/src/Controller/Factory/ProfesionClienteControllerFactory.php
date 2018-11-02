<?php
namespace ProfesionCliente\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ProfesionCliente\Controller\ProfesionClienteController;
use ProfesionCliente\Service\ProfesionClienteManager;
use Clientes\Service\ClientesManager;

/**
 * Description of ProfesionClienteControllerFactory
 *
 * @author mariano
 */
class ProfesionClienteControllerFactory implements FactoryInterface {
    

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $profesionclienteManager = $container->get(ProfesionClienteManager::class);
        $clientesManager = $container->get(ClientesManager::class);
        // Instantiate the service and inject dependencies
        return new ProfesionClienteController($entityManager, $profesionclienteManager, $clientesManager);
    }    
}
