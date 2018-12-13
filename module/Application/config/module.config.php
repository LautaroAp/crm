<?php

/**
 * En esta clase se configuran las rutas con el controlador correspondiente
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            // Gestion Clientes
             'gestionClientes' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/clientes',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'gestionClientes',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'listado' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/listado',
                            'defaults' => [
                                'controller' => \Clientes\Controller\ClientesController::class,
                                'action' => 'index',
                            ],
                        ], 
                    ],
                    'agregar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'controller' => \Clientes\Controller\ClientesController::class,
                                'action' => 'add',
                            ],
                        ],
                    ],
                    'profesioncliente' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/profesioncliente',
                            'defaults' => [
                                'controller' => \ProfesionCliente\Controller\ProfesionClienteController::class,
                                'action' => 'index',
                            ],
                        ], 
                     ],
                     'categoriacliente' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/categoriacliente',
                            'defaults' => [
                                'controller' => \CategoriaCliente\Controller\CategoriaClienteController::class,
                                'action' => 'index',
                            ],
                        ], 
                     ],
                     'tipoevento' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/actividades',
                            'defaults' => [
                                'controller' => \TipoEvento\Controller\TipoEventoController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'backup' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/backup',
                            'defaults' => [
                                'controller' => \Clientes\Controller\ClientesController::class,
                                'action' => 'backup',
                            ],
                        ],
                    ],
                ],
             ],
             // Gestion Proveedores
             'gestionProveedores' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/proveedores',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'gestionProveedores',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'listado' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/listado',
                            'defaults' => [
                                'controller' => \Clientes\Controller\ClientesController::class,
                                'action' => 'index',
                            ],
                        ], 
                    ],
                ],
             ],
             // Gestion Empresa
             'gestionEmpresa' => [
                 'type' => Literal::class,
                 'options' => [
                     'route' => '/empresa',
                     'defaults' => [
                         'controller' => Controller\IndexController::class,
                         'action' => 'gestionEmpresa',
                     ],
                 ],
                 'may_terminate' => true,
                 'child_routes' => [
                    'configuracion' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/configuracion',
                            'defaults' => [
                                'controller' => \Empresa\Controller\EmpresaController::class,
                                'action' => 'index',
                            ],
                        ], 
                     ],
                    'ventas' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ventas',
                            'defaults' => [
                                'controller' => \Evento\Controller\EventoVentaController::class,
                                'action' => 'index',
                            ],
                        ], 
                     ],
                     'backup' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/backup',
                            'defaults' => [
                                'controller' => \Empresa\Controller\EmpresaController::class,
                                'action' => 'backup',
                            ],
                        ],
                    ],
                     // Gestion Licencias
                     'gestionLicencias' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/licencias',
                            'defaults' => [
                                'controller' => \Application\Controller\IndexController::class,
                                'action' => 'gestionLicenciasEmpresa',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'listado' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/listado',
                                    'defaults' => [
                                        'controller' => \Licencia\Controller\LicenciaController::class,
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                            'backup' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/backup',
                                    'defaults' => [
                                        'controller' => \Licencia\Controller\LicenciaController::class,
                                        'action' => 'backup',
                                    ],
                                ],
                            ],
                            'agregar' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/add',
                                    'defaults' => [
                                        'controller' => \Licencia\Controller\LicenciaController::class,
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                        ], 
                    ],
                    // Gestion Productos
                    'gestionProductos' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/productos',
                            'defaults' => [
                                'controller' => \Application\Controller\IndexController::class,
                                'action' => 'gestionProductosEmpresa',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'listado' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/listado',
                                    'defaults' => [
                                        'controller' => \Producto\Controller\ProductoController::class,
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                            'agregar' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/add',
                                    'defaults' => [
                                        'controller' => \Producto\Controller\ProductoController::class,
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                            'editar' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/edit[/:id]',
                                    'defaults' => [
                                        'controller' => \Producto\Controller\ProductoController::class,
                                        'action' => 'edit',
                                    ],
                                    'constraints' => [
                                        'id' => '[0-9]\d*',
                                    ],
                                ],
                            ],
                            'categorias' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/categorias',
                                    'defaults' => [
                                        'controller' => \CategoriaProducto\Controller\CategoriaProductoController::class,
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                        ], 
                    ],
                    // Gestion Servicios
                    'gestionServicios' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/servicios',
                            'defaults' => [
                                'controller' => \Application\Controller\IndexController::class,
                                'action' => 'gestionServiciosEmpresa',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'listado' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/listado',
                                    'defaults' => [
                                        'controller' => \Servicio\Controller\ServicioController::class,
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                            'agregar' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/add',
                                    'defaults' => [
                                        'controller' => \Servicio\Controller\ServicioController::class,
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                        ], 
                    ],
                 ],
              ],
              // Herramientas
              'herramientas' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/herramientas',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'herramientas',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ejecutivos' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ejecutivos',
                            'defaults' => [
                                'controller' => \Ejecutivo\Controller\EjecutivoController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'inactivos' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/inactivos',
                            'defaults' => [
                                'controller' => \Ejecutivo\Controller\EjecutivoInactivoController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'backup' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/backup',
                            'defaults' => [
                                'controller' => \Ejecutivo\Controller\EjecutivoController::class,
                                'action' => 'backup',
                            ],
                        ],
                    ],
                ],
             ],
        
            ],
            
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ]
    ],
    'service_manager' => [
        'factories' => [
            Service\IndexManager::class => Service\Factory\IndexManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
