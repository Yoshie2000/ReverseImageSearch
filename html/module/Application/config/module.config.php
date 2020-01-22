<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Console\Factory\CrawlControllerFactory;
use Application\Controller\Factory\IndexControllerFactory;
use Application\Controller\Factory\TestControllerFactory;
use Application\Service\Factory\AbstractCrawlStrategyFactory;
use Application\Service\Factory\RabbitMQServiceFactory;
use Application\Service\Factory\URLServiceFactory;
use Application\Service\RabbitMQService;
use Application\Service\RabbitMQServiceInterface;
use Application\Service\URLService;
use Application\Service\URLServiceInterface;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'console'         => require 'console.config.php',
    'router'          => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'url'  => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/url/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'url',
                    ],
                ],
            ],
            'test' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/test[/:param]',
                    'defaults' => [
                        'controller' => Controller\TestController::class,
                        'action'     => 'test',
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            Controller\IndexController::class => IndexControllerFactory::class,
            Controller\TestController::class  => TestControllerFactory::class,
            Console\CrawlController::class    => CrawlControllerFactory::class,
        ]
    ],
    'view_manager'    => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'application/index/url'   => __DIR__ . '/../view/application/index/url.phtml',
            'application/test/index'  => __DIR__ . '/../view/application/test/index.phtml',
            'application/test/test'   => __DIR__ . '/../view/application/test/test.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'aliases'            => [
            URLServiceInterface::class      => URLService::class,
            RabbitMQServiceInterface::class => RabbitMQService::class
        ],
        'factories'          => [
            URLService::class      => URLServiceFactory::class,
            RabbitMQService::class => RabbitMQServiceFactory::class,
        ],
        'abstract_factories' => [
            AbstractCrawlStrategyFactory::class,
        ]
    ],
];
