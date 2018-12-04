<?php
/**
 * Esta clase se encarga de configurar las rutas correspondientes
 * al modulo persona.
 */

namespace Persona;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' =>[],
    ],
     'view_manager' => array(
         'template_path_stack' => array(
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\PersonaManager::class => Service\Factory\PersonaManagerFactory::class,
        ),
    )
 ];