<?php
/**
 * Esta clase configura las rutas del modulo CategoriaLicencia
 */

namespace CategoriaLicencia;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Application;


return [
   'router' => [
        'routes' => [
            'categoriaLicencia2' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/productos/categoriaLicencia',
                    'defaults' => [
                        'controller'    => Controller\CategoriaLicenciaController::class,
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
            Controller\CategoriaLicenciaController::class => Controller\Factory\CategoriaLicenciaControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'categoriaLicencia' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\CategoriaLicenciaManager::class => Service\Factory\CategoriaLicenciaManagerFactory::class,
        ),
    )
 ];

