<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Presupuesto;

use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Application;


return [
   
   'router' => [
        'routes' => [
            'presupuesto' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/presupuesto',
                    'defaults' => [
                        'controller' => \Application\Controller\IndexController::class,
                        'action' => 'gestionPresupuestos',
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
                                'controller' => \Presupuesto\Controller\PresupuestoController::class,
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
            Controller\PresupuestoController::class => Controller\Factory\PresupuestoControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'presupuesto' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\PresupuestoManager::class => Service\Factory\PresupuestoManagerFactory::class,
        ),
    )
 ];

