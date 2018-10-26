<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'backup' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/backup',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'backup',
                    ],
                ],
                
             ],
            'clientes' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/clientes',
                        'route' => '/clientes/[page/:page]',
                         'constraints' => [
                            'page' => '[0-9]*',
                             ],
                    'defaults' => [
                        'controller' => \Clientes\Controller\ClientesController::class,
                        'action' => 'index',
                    ],
                ],
            ],
             'evento' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/evento',
                    'defaults' => [
                        'controller' => \Evento\Controller\EventoController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'ejecutivos' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/ejecutivos',
                    'defaults' => [
                        'controller' => \Ejecutivo\Controller\EjecutivoController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            
            'licencia' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/licencia',
                    'defaults' => [
                        'controller' => \Licencia\Controller\LicenciaController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'pais' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/pais',
                    'defaults' => [
                        'controller' => \Pais\Controller\PaisController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'tipoevento' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/tipoevento',
                    'defaults' => [
                        'controller' => \TipoEvento\Controller\TipoEventoController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'utilidades' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/utilidades',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'utilidades',
                    ],
                ],
                
             ],
            'empresa' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/empresa',
                    'defaults' => [
                        'controller' => Controller\EmpresaController::class,
                        'action' => 'index',
                    ],
                ],
                
             ],
            'servicio' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/servicio',
                    'defaults' => [
                        'controller' => Controller\ServicioController::class,
                        'action' => 'index',
                    ],
                ],
                
             ],
            'usuarios' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/usuarios',
                    'defaults' => [
                        'controller' => Controller\UsuarioController::class,
                        'action' => 'index',
                    ],
                ],
                
             ],
            'ventas' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/ventas',
                    'defaults' => [
                        'controller' => \Evento\Controller\EventoVentaController::class,
                        'action' => 'index',
                    ],
                ], 
             ],
            'gestion' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/gestion',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'gestion',
                    ],
                ],
                
             ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ]
    ],
    'service_manager' => [
        'factories' => [
            Service\IndexManager::class => Service\Factory\IndexManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
