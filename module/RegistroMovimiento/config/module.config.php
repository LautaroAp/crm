<?php
/**
 * Esta clase configura las rutas del modulo RegistroMovimiento
 */

namespace RegistroMovimiento;

use Zend\Router\Http\Segment;

return [
   'router' => [
        'routes' => [
            'registroMovimiento' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/registroMovimiento',
                    'defaults' => [
                        'controller'    => Controller\RegistroMovimientoController::class,
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
            Controller\RegistroMovimientoController::class => Controller\Factory\RegistroMovimientoControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'registroMovimiento' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\RegistroMovimientoManager::class => Service\Factory\RegistroMovimientoManagerFactory::class,
        ),
    )
 ];

