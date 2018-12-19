<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Producto;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Application;


return [
   
   'router' => [
        'routes' => [
            'producto' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/producto',
                    'defaults' => [
                        'controller' => \Application\Controller\IndexController::class,
                        'action' => 'gestionProductos',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'listado' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/listado',
                            'defaults' => [
                                'controller' => \Producto\Controller\ProductoController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'agregar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'controller' => \Producto\Controller\ProductoController::class,
                                'action' => 'add',
                            ],
                        ],
                    ],
                    'editar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit[/:id]',
                            'defaults' => [
                                'controller' => \Producto\Controller\ProductoController::class,
                                'action' => 'edit',
                            ],
                            'constraints' => [
                                'id' => '[0-9]\d*',
                            ],
                        ],
                    ],
                    'eliminar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/remove[/:id]',
                            'defaults' => [
                                'controller' => \Producto\Controller\ProductoController::class,
                                'action' => 'remove',
                            ],
                            'constraints' => [
                                'id' => '[0-9]\d*',
                            ],
                        ],
                    ],
                    'categorias' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/categorias',
                            'defaults' => [
                                'controller' => \CategoriaProducto\Controller\CategoriaProductoController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'page' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/page[/:id[/:estado]]',
                            'defaults' => [
                                'controller' => \Producto\Controller\ProductoController::class,
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
            Controller\ProductoController::class => Controller\Factory\ProductoControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'producto' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\ProductoManager::class => Service\Factory\ProductoManagerFactory::class,
        ),
    )
 ];

