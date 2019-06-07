<?php
/**
 * Esta clase configura las rutas del modulo Comprobante
 */

namespace Comprobante;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Application;


return [
   'router' => [
        'routes' => [
            'comprobante' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/comprobante',
                    'defaults' => [
                        'controller'    => Controller\ComprobanteController::class,
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
            Controller\ComprobanteController::class => Controller\Factory\ComprobanteControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'Comprobante' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\ComprobanteManager::class => Service\Factory\ComprobanteManagerFactory::class,
        ),
    )
 ];

