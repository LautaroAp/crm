<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Provincia;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;


return [
   
   'router' => [
        'routes' => [
           'provincia' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/provincia',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\ProvinciaController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],       
    
    
 'controllers' => array(
        'factories' => [
            Controller\ProvinciaController::class => Controller\Factory\ProvinciaControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'provincia' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\ProvinciaManager::class => Service\Factory\ProvinciaManagerFactory::class,
        ),
    )
 ];

