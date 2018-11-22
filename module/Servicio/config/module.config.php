<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Servicio;

use Zend\Router\Http\Segment;


return [
   
   'router' => [
        'routes' => [
            'servicio' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/servicio[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\ServicioController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],       
    
    
 'controllers' => array(
        'factories' => [
            Controller\ServicioController::class => Controller\Factory\ServicioControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'servicio' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\ServicioManager::class => Service\Factory\ServicioManagerFactory::class,
        ),
    )
 ];

