<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CuentaCorriente;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' =>[
            'cuentacorriente' => [
                'type'    => Segment::class,
                'options' => [
                    'route' =>'/cuentacorriente',
                    'defaults' => [
                        'controller' => Controller\CuentaCorrienteController::class, 
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajax' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/ajax[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\CuentaCorrienteController::class,                               
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                ],
            ],
            'ccCliente' => [
                'type'    => Segment::class,
                'options' => [
                    'route' =>'/ccCliente',
                    'defaults' => [
                        'controller' => Controller\CuentaCorrienteController::class, 
                        'action' => 'ccCliente',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajax' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/ajax[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\CuentaCorrienteController::class,                               
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                ],
            ],
            'ccProveedor' => [
                'type'    => Segment::class,
                'options' => [
                    'route' =>'/ccProveedor',
                    'defaults' => [
                        'controller' => Controller\CuentaCorrienteController::class, 
                        'action' => 'ccProveedor',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajax' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/ajax[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\CuentaCorrienteController::class,                               
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => array(
        'factories' => [
            Controller\CuentaCorrienteController::class => Controller\Factory\CuentaCorrienteControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
            'cuentacorriente' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\CuentaCorrienteManager::class => Service\Factory\CuentaCorrienteManagerFactory::class,
        ),
    )
 ];