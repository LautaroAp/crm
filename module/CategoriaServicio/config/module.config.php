<?php
/**
 * Esta clase configura las rutas del modulo CategoriaServicio
 */

namespace CategoriaServicio;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Application;


return [
   'router' => [
        'routes' => [
            'categoriaServicio' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/productos/categoriaServicio',
                    'defaults' => [
                        'controller'    => Controller\CategoriaServicioController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'editar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit[/:id]',
                            'defaults' => [
                                'action' => 'edit',
                            ],
                            'constraints' => [
                                'id' => '[0-9]\d*',
                            ],
                        ],
                    ],
                    'borrar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/remove[/:id]',
                            'defaults' => [
                                'action' => 'remove',
                            ],
                            'constraints' => [
                                'id' => '[0-9]\d*',
                            ],
                        ],
                    ],
                    'page' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/page[/:id[/:estado]]',
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
            Controller\CategoriaServicioController::class => Controller\Factory\CategoriaServicioControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'categoriaServicio' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\CategoriaServicioManager::class => Service\Factory\CategoriaServicioManagerFactory::class,
        ),
    )
 ];

