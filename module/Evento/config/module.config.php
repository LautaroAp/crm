<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Evento;

use Zend\Router\Http\Segment;

return [

    'router' => [
        'routes' => [
            'evento' => [
                'type' => Segment::class,
                'options' => [
                    //'route' => '/evento',
                     'route' => '/evento[/:action[/:tipo[/:id]]]',
                    'defaults' => [
                        'controller' => Controller\EventoController::class,
                        'action' => 'index',
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
                    'agregar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/add[/:tipo[/:id]]',
                            'defaults' => [
                                'action' => 'add',
                            ],
                        ],
                    ],
                    'borrar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete[/:id]',
                            'defaults' => [
                                'action' => 'delete',
                            ],
                            'constraints' => [
                                'id' => '[0-9]\d*',
                            ],
                        ],
                    ],
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
            'pageventas' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/pageventas[/:tipo[/:id]]',
                    'defaults' => [
                        'controller' => Controller\EventoVentaController::class,
                        'action' => 'index',
                    ],
                    'constraints' => [
                        'id' => '[0-9]\d*',
                    ],
                ],
            ],
                // 'may_terminate' => true,
                // 'child_routes' => [
                //     'ajax' => [
                //         'type' => Segment::class,
                //         'options' => [
                //             'route' => '/ajax[/:action[/:tipo]]',
                //             'defaults' => [
                //                 'controller' => Controller\EventoVentaController::class,
                //             ],
                //         ],
                //     ],
                // ],

            'ventas' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => 'eventos/ventas[/:id]',
                        'defaults' => [
                            'controller' => Controller\EventoVentaController::class,
                            'action' => 'index',
                        ],
                        'constraints' => [
                            'id' => '[0-9]\d*',
                        ],
                    ],
                    // 'may_terminate' => true,
                    // 'child_routes' => [
                    //     'ajax' => [
                    //         'type' => Segment::class,
                    //         'options' => [
                    //             'route' => '/ajax/:action[/:tipo]',
                    //             'defaults' => [
                    //                 'controller' => Controller\EventoVentaController::class,
                    //             ],
                    //         ],
                    //     ],
                    // ],
                // ],

            ],
        ],
    ],

    'controllers' => array(
        'factories' => [
            Controller\EventoController::class => Controller\Factory\EventoControllerFactory::class,
            Controller\EventoVentaController::class => Controller\Factory\EventoVentaControllerFactory::class,

        ],
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'evento' => __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            Service\EventoManager::class => Service\Factory\EventoManagerFactory::class,
            Service\EventoVentaManager::class => Service\Factory\EventoVentaManagerFactory::class,

        ),
    )
];

