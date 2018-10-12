<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CategoriaCliente;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Application;


return [
   
   'router' => [
        'routes' => [
            'categoriacliente' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/categoriacliente[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\CategoriaClienteController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],       
    
    
 'controllers' => array(
        'factories' => [
            Controller\CategoriaClienteController::class => Controller\Factory\CategoriaClienteControllerFactory::class,
        ],
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'categoriacliente' => __DIR__ . '/../view',
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\CategoriaClienteManager::class => Service\Factory\CategoriaClienteManagerFactory::class,
        ),
    )
 ];

