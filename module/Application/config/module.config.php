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
                'type' => Segment::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'menu',
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
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/listado[/:tipo]',
                            'defaults' => [
                                'controller' => \Clientes\Controller\ClientesController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'agregar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/add[/:tipo]',
                            'defaults' => [
                                'controller' => \Clientes\Controller\ClientesController::class,
                                'action' => 'add',
                            ],
                        ],
                    ],
                    'profesion' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/profesion',
                            'defaults' => [
                                'controller' => \Profesion\Controller\ProfesionController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'categorias' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/categorias[/:tipo[/:id]]',
                            'defaults' => [
                                'controller' => \Categoria\Controller\CategoriaController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'eventos' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/eventos[/:tipo]',
                            'defaults' => [
                                'controller' => \Evento\Controller\EventoVentaController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    //  Gestion Actividades
                    'gestionEventosClientes' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/actividades',
                            'defaults' => [
                                'controller' => \Application\Controller\IndexController::class,
                                'action' => 'gestionEventosClientes',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'tipoeventoCliente' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '[/:tipo[/:id]]',
                                    'defaults' => [
                                        'controller' => \TipoEvento\Controller\TipoEventoController::class,
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
                                    'route' => '/categorias[/:tipo[/:id]]',
                                    'defaults' => [
                                        'controller' => \Categoria\Controller\CategoriaController::class,
                                        'action' => 'index',
                                    ],
                                ],
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
                                'controller' => \Proveedor\Controller\ProveedorController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'agregar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/add[/:tipo]',
                            'defaults' => [
                                'controller' => \Proveedor\Controller\ProveedorController::class,
                                'action' => 'add',
                            ],
                        ],
                    ],
                    'categorias' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/categorias[/:tipo[/:id]]',
                            'defaults' => [
                                'controller' => \Categoria\Controller\CategoriaController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'eventos' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/eventos[/:tipo]',
                            'defaults' => [
                                'controller' => \Evento\Controller\EventoVentaController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    //  Gestion Actividades
                    'gestionEventosProveedores' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/actividades',
                            'defaults' => [
                                'controller' => \Application\Controller\IndexController::class,
                                'action' => 'gestionEventosProveedores',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'tipoeventoProveedor' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '[/:tipo[/:id]]',
                                    'defaults' => [
                                        'controller' => \TipoEvento\Controller\TipoEventoController::class,
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
                                    'route' => '/categorias[/:tipo[/:id]]',
                                    'defaults' => [
                                        'controller' => \Categoria\Controller\CategoriaController::class,
                                        'action' => 'index',
                                    ],
                                ],
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
                        'may_terminate' => true,
                        'child_routes' => [
                            'editar' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/editar[/:id]',
                                    'defaults' => [
                                        'controller' => \Empresa\Controller\EmpresaController::class,
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'eventos' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/eventos',
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
                ],
            ],
            // 'ventas' => [
            //     'type' => Literal::class,
            //     'options' => [
            //         'route' => '/ventas',
            //         'defaults' => [
            //             'controller' => \Evento\Controller\EventoVentaController::class,
            //             'action' => 'index',
            //         ],
            //     ],
            // ],
            'getTipos'=>[
                'type' => Segment::class,
                'options' => [
                    'route' => '/getTipos[/:tipo]',
                    'defaults' => [
                        'controller' => \Evento\Controller\EventoVentaController::class,
                        'action' => 'getTipos',
                    ],
                ],
            ],
            // Gestion Productos y Servicios
           'gestionProductosServicios' => [
               'type' => Literal::class,
               'options' => [
                   'route' => '/productosservicios',
                   'defaults' => [
                       'controller' => Controller\IndexController::class,
                       'action' => 'gestionProductosServicios',
                   ],
               ],
               'may_terminate' => true,
               'child_routes' => [
                     // Gestion Licencias
                     'gestionLicencias' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/licencias',
                            'defaults' => [
                                'controller' => \Application\Controller\IndexController::class,
                                'action' => 'gestionLicencias',
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
                                    'route' => '/add[/:tipo]',
                                    'defaults' => [
                                        'controller' => \Licencia\Controller\LicenciaController::class,
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                            'categorias' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/categorias[/:tipo[/:id]]',
                                    'defaults' => [
                                        'controller' => \Categoria\Controller\CategoriaController::class,
                                        'action' => 'index',
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
                               'action' => 'gestionProductos',
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
                                   'route' => '/add[/:tipo]',
                                   'defaults' => [
                                       'controller' => \Producto\Controller\ProductoController::class,
                                       'action' => 'add',
                                   ],
                               ],
                           ],
                           'editar' => [
                               'type' => Segment::class,
                               'options' => [
                                   'route' => '/edit[/:tipo[/:id]]',
                                   'defaults' => [
                                       'controller' => \Producto\Controller\ProductoController::class,
                                       'action' => 'edit',
                                   ],
                                   'constraints' => [
                                       'id' => '[0-9]\d*',
                                   ],
                               ],
                           ],
                           'eliminar' => [
                               'type' => Segment::class,
                               'options' => [
                                   'route' => '/remove[/:id]',
                                   'defaults' => [
                                       'controller' => \Producto\Controller\ProductoController::class,
                                       'action' => 'remove',
                                   ],
                                   'constraints' => [
                                       'id' => '[0-9]\d*',
                                   ],
                               ],
                           ],

                           'categorias' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/categorias[/:tipo[/:id]]',
                                'defaults' => [
                                    'controller' => \Categoria\Controller\CategoriaController::class,
                                    'action' => 'index',
                                    ],
                                ],
                            ],
                           'backup' => [
                               'type' => Segment::class,
                               'options' => [
                                   'route' => '/backup',
                                   'defaults' => [
                                       'controller' => \Producto\Controller\ProductoController::class,
                                       'action' => 'backup',
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
                               'action' => 'gestionServicios',
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
                                   'route' => '/add[/:tipo]',
                                   'defaults' => [
                                       'controller' => \Servicio\Controller\ServicioController::class,
                                       'action' => 'add',
                                   ],
                               ],
                           ],
                           'editar' => [
                               'type' => Segment::class,
                               'options' => [
                                   'route' => '/edit[/:tipo[/:id]]',
                                   'defaults' => [
                                       'controller' => \Servicio\Controller\ServicioController::class,
                                       'action' => 'edit',
                                   ],
                                   'constraints' => [
                                       'id' => '[0-9]\d*',
                                   ],
                               ],
                           ],
                           'eliminar' => [
                               'type' => Segment::class,
                               'options' => [
                                   'route' => '/remove[/:id]',
                                   'defaults' => [
                                       'controller' => \Servicio\Controller\ServicioController::class,
                                       'action' => 'remove',
                                   ],
                                   'constraints' => [
                                       'id' => '[0-9]\d*',
                                   ],
                               ],
                           ],
                           'categorias' => [
                               'type' => Segment::class,
                               'options' => [
                                   'route' => '/categorias[/:tipo[/:id]]',
                                   'defaults' => [
                                       'controller' => \Categoria\Controller\CategoriaController::class,
                                       'action' => 'index',
                                   ],
                               ],
                           ],
                           'backup' => [
                               'type' => Segment::class,
                               'options' => [
                                   'route' => '/backup',
                                   'defaults' => [
                                       'controller' => \Servicio\Controller\ServicioController::class,
                                       'action' => 'backup',
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
                    'tipoiva' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/tipoIva',
                            'defaults' => [
                                'controller' => \Iva\Controller\IvaController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'condicioniva' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/categorias[/:tipo[/:id]]',
                            'defaults' => [
                                'controller' => \Categoria\Controller\CategoriaController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'formaspago' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/formasPago',
                            'defaults' => [
                                'controller' => \FormaPago\Controller\FormaPagoController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'formasenvio' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/formasEnvio',
                            'defaults' => [
                                'controller' => \FormaEnvio\Controller\FormaEnvioController::class,
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
            'categoria' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/categoria[/:tipo[/:id]]',
                    'defaults' => [
                        'controller' => \Categoria\Controller\CategoriaController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'actividades' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/actividades[/:tipo[/:id]]',
                    'defaults' => [
                        'controller' => \TipoEvento\Controller\TipoEventoController::class,
                        'action' => 'index',
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
