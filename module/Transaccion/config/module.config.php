<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Transaccion;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' =>[
            'ajax' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/ajax[/:action[/:id[/:id2]]]',
                    'defaults' => [
                        'controller' => \Transaccion\Controller\TransaccionController::class,                             
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                        'id2' => '[a-zA-Z0-9_-]*',
                    ],
                ],
            ],
        ],
    ],
     'view_manager' => array(
         'template_path_stack' => array(
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\TransaccionManager::class => Service\Factory\TransaccionManagerFactory::class,
        ),
    )
 ];