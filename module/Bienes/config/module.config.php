<?php
/**
 */


namespace Bienes;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Application;

return [
    'router' => [
        'routes' =>[
            'bienes' => [
                'type'    => Segment::class,
                'options' => [
                    'route' =>'[/:transaccion][/:accion][/:id]/bienes[/:tipo]',
                    'defaults' => [
                        'controller' => \Bienes\Controller\BienesController::class,
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
                                'controller' => Controller\BienesController::class,                               
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
            Controller\BienesController::class => Controller\Factory\BienesControllerFactory::class,
        ],
     ),
     'view_manager' => array(
        'template_path_stack' => array(
            'bienes' => __DIR__ . '/../view',
        ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\BienesManager::class => Service\Factory\BienesManagerFactory::class,
        ),
    )
 ];