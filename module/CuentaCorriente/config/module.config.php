<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CuentaCorriente;

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
            Service\CuentaCorrienteManager::class => Service\Factory\CuentaCorrienteManagerFactory::class,
        ),
    )
 ];