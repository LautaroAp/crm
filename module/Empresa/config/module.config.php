<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Empresa;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;


return [
   
   'router' => [
        'routes' => [
            'empresa2' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/empresa2',
                    'defaults' => [
                        'controller'    => Controller\EmpresaController::class,
                        'action'        => 'index',
                    ],
                ],
                // 'may_terminate' => true,
                // 'child_routes' => [
                //     'editar' => [
                //         'type' => Segment::class,
                //         'options' => [
                //             'route' => '/editar[/:id]',
                //             'defaults' => [
                //                 'controller' => \Empresa\Controller\EmpresaController::class,
                //                 'action' => 'edit',
                //             ],
                //         ],
                //     ],
                // ],
            ],
        ],
    ],       
    
    
 'controllers' => array(
        'factories' => [
            Controller\EmpresaController::class => Controller\Factory\EmpresaControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'empresa2' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\EmpresaManager::class => Service\Factory\EmpresaManagerFactory::class,
        ),
    )
 ];

