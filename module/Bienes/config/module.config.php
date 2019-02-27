<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Bienes;

use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Application;


return [
    'router' => [
        'routes' =>[],
    ],
     'view_manager' => array(
         'template_path_stack' => array(
         ),
     ),
    'service_manager' => array(
        'factories' => array(
            Service\BienesManager::class => Service\Factory\BienesManagerFactory::class,
        ),
    )
 ];