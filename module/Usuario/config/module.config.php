<?php
/**
 * Configuracion de las rutas y servicios del modulo Usuario
 */

namespace Usuario;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'usuarios' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/usuarios[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\UsuarioController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'buscar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/seach',
                            'defaults' => [
                                'action' => 'search',
                            ],
                        ],
                    ],
                    'page' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/page[/:id]',
                            'defaults' => [
                                'action' => 'index',
                            ],
                            'constraints' => [
                                'id' => '[0-9]\d*',
                            ],
                        ],
                    ],
                ],
            
            ],
        ],
    ],
    'controllers' => array(
        'factories' => [
            Controller\UsuarioController::class => Controller\Factory\UsuarioControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'usuarios' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\UsuarioManager::class => Service\Factory\UsuarioManagerFactory::class,
        ),
    )
 ];