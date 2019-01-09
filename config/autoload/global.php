<?php
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;

return [
    // Session configuration.
    'session_config' => [
        'cookie_lifetime'     => 60*60*1, // Session cookie will expire in 1 hour.
        'gc_maxlifetime'      => 60*60*24*30, // How long to store session data on server (for 1 month).        
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => '127.0.0.1',
                    'user'     => 'root',
                    'password' => 'root',
                    'dbname'   => 'crm',
                    //'dbname'   => 'crm_produccion',
                    'charset' => 'utf8', 
                ]
            ],
        ],
    ],
];
