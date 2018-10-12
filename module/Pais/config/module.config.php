<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Pais;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;


return [
   
   'router' => [
        'routes' => [
            'pais' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/pais[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\PaisController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],       
    
    
 'controllers' => array(
        'factories' => [
            Controller\PaisController::class => Controller\Factory\PaisControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'pais' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\PaisManager::class => Service\Factory\PaisManagerFactory::class,
        ),
    )
 ];

