<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Clientes;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'clientes' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/clientes',
                    'defaults' => [
                        'controller' => Controller\ClientesController::class,
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
                     'ajax' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/ajax[/:action[/:id[/:id2]]]',
                            'defaults' => [
                                'controller' => Controller\ClientesController::class,                               
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]*',
                                'id2' => '[a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                    'agregar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/add',
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
                    'modificarEstado' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modificarEstado[/:id]',
                            'defaults' => [
                                'action' => 'modificarEstado',
                            ],
                            'constraints' => [
                                'id' => '[0-9]\d*',
                            ],
                        ],
                    ],
                    'ficha' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ficha[/:id]',
                            'defaults' => [
                                'action' => 'ficha',
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
                            'route' => '/page[/:id[/:estado]]',
                            'defaults' => [
                                'action' => 'index',
                            ],
                            'constraints' => [
                                'id' => '[0-9]\d*',
                            ],
                        ],
                    ],
                    'pageinactivos' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/pageinactivos[/:id[/:estado]]',
                            'defaults' => [
                                'controller' => Controller\ClientesInactivosController::class,
                                'action' => 'index',
                            ],
                            'constraints' => [
                                'id' => '[0-9]\d*',
                            ],
                        ],
                    ],
                    'inactivos' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/inactivos[/:id]',
                            'defaults' => [
                                'controller' => Controller\ClientesInactivosController::class,
                                'action' => 'index',
                            ],
                        ],
                        'constraints' => [
                            'id' => '[0-9]\d*',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ClientesController::class => Controller\Factory\ClientesControllerFactory::class,
            Controller\ClientesInactivosController::class => Controller\Factory\ClientesInactivosControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\ClientesManager::class => Service\Factory\ClientesManagerFactory::class,
            Service\ClientesInactivosManager::class => Service\Factory\ClientesInactivosManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
