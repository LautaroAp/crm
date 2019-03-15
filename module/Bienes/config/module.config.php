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
                    'route' =>'[/:transaccion]/bienes[/:tipo]',
                    'defaults' => [
                        'controller' => \Bienes\Controller\BienesController::class,
                        'action' => 'index',
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