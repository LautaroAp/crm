<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Proveedor;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'proveedores' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/proveedores',
                    'defaults' => [
                        'controller' => \Application\Controller\IndexController::class,
                        'action' => 'gestionProveedores',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'editar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit[/:tipo[/:id]]',
                            'defaults' => [
                                'controller' => Controller\ProveedorController::class,
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
                                'controller' => Controller\ProveedorController::class,                               
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
                            'route' => '/add[/:tipo]',
                            'defaults' => [
                                'controller' => Controller\ProveedorController::class,
                                'action' => 'add',
                            ],
                        ],
                    ],
                    'borrar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete[/:id]',
                            'defaults' => [
                                'controller' => Controller\ProveedorController::class,
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
                                'controller' => Controller\ProveedorController::class,
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
                            'route' => '/listado/ficha[/:id]',
                            'defaults' => [
                                'controller' => Controller\ProveedorController::class,
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
                                'controller' => Controller\ProveedorController::class,
                                'action' => 'search',
                            ],
                        ],
                    ],
                    'page' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/page[/:id[/:estado]]',
                            'defaults' => [
                                'controller' => Controller\ProveedorController::class,
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
                                'controller' => Controller\ProveedorInactivoController::class,
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
                                'controller' => Controller\ProveedorInactivoController::class,
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
            Controller\ProveedorController::class => Controller\Factory\ProveedorControllerFactory::class,
            Controller\ProveedorInactivoController::class => Controller\Factory\ProveedorInactivoControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\ProveedorManager::class => Service\Factory\ProveedorManagerFactory::class,
            Service\ProveedorInactivoManager::class => Service\Factory\ProveedorInactivoManagerFactory::class,
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
