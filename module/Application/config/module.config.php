<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
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
            'reviews' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/reviews[/:action]',
                    'defaults' => [
                        'controller' => Controller\ReviewsController::class,
                        'action' => 'index',
                    ],
                ]
            ],
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'sync-run-cron' => [
                    'options' => [
                        'route' => 'sync run-cron',
                        'defaults' => [
                            'controller' => Console\SyncController::class,
                            'action' => 'run',
                        ],
                    ],
                ],
                'sync-load-apps' => [
                    'options' => [
                        'route' => 'sync load-apps',
                        'defaults' => [
                            'controller' => Console\SyncController::class,
                            'action' => 'loadApps',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ReviewsController::class => InvokableFactory::class,
            Console\SyncController::class => InvokableFactory::class,
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
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
        ]
    ],
    'doctrine' => [
        'driver' => [
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'my_annotation_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [

                    __DIR__ . '/../src/Entity',
                ],
            ],

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => [
                'drivers' => [
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    'Application\Entity' => 'my_annotation_driver',
                ],
            ],
        ],
    ],
    'rabbitmq_module' => [
        'producer' => [
            'producer_name' => [
                'connection' => 'default', // the connection name
                'exchange' => [
                    'type' => 'direct',
                    'name' => 'exchange-name',
                    'durable' => true,      // (default)
                    'auto_delete' => false, // (default)
                    'internal' => false,    // (default)
                    'no_wait' => false,     // (default)
                    'declare' => true,      // (default)
                    'arguments' => [],      // (default)
                    'ticket' => 0,          // (default)
                    'exchange_binds' => []  // (default)
                ],
                'queue' => [ // optional queue
                    'name' => 'queue-name', // can be an empty string,
                    'type' => null,         // (default)
                    'passive' => false,     // (default)
                    'durable' => true,      // (default)
                    'auto_delete' => false, // (default)
                    'exclusive' => false,   // (default)
                    'no_wait' => false,     // (default)
                    'arguments' => [],      // (default)
                    'ticket' => 0,          // (default)
                    'routing_keys' => []    // (default)
                ],
                'auto_setup_fabric_enabled' => true // auto-setup exchanges and queues
            ]
        ]
    ]
];
