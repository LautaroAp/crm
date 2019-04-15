<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace DatoAdicional;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Application;

return [ 
   'router' => [
        'routes' => [
            'datoadicional' => [
                'type'    => Segment::class,
                'options' => [
                    'route' =>'/datosadicionales',
                    'defaults' => [
                        'controller'    => Controller\DatoAdicionalController::class,
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
                    'agregar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/add[/:id]',
                            'defaults' => [
                                'action' => 'add',
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
                            'route' => '/page[/:tipo[/:id]]',
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
            Controller\DatoAdicionalController::class => Controller\Factory\DatoAdicionalControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'DatoAdicional' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\DatoAdicionalManager::class => Service\Factory\DatoAdicionalManagerFactory::class,
        ),
    )
 ];

