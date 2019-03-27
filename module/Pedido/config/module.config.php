<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Pedido;

use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Application;


return [
   
   'router' => [
        'routes' => [
            'pedido' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/pedido',
                    'defaults' => [
                        'controller' => \Pedido\Controller\PedidoController::class,
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
                            'route' => '/add[/:id]',
                            'defaults' => [
                                'action' => 'add',
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
                    'addItem' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/item[/:id]',
                            'defaults' => [
                                'action' => 'addItem',
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
                                'controller' => \Pedido\Controller\PedidoController::class,
                                'action' => 'index',
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
                                'controller' => \Pedido\Controller\PedidoController::class,                             
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]*',
                                'id2' => '[a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],       
    
    
 'controllers' => array(
        'factories' => [
            Controller\PedidoController::class => Controller\Factory\PedidoControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'pedido' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\PedidoManager::class => Service\Factory\PedidoManagerFactory::class,
        ),
    )
 ];

