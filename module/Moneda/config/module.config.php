<?php
/**
 * Esta clase configura las rutas del modulo Moneda
 */

namespace Moneda;

use Zend\Router\Http\Segment;

return [
   'router' => [
        'routes' => [
            'moneda' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/productos/moneda',
                    'defaults' => [
                        'controller'    => Controller\MonedaController::class,
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
            Controller\MonedaController::class => Controller\Factory\MonedaControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'moneda' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\MonedaManager::class => Service\Factory\MonedaManagerFactory::class,
        ),
    )
 ];

