<?php

namespace Application;

use Application\Console\CrawlController;
use Application\Console\Factory\CrawlControllerFactory;
use Application\Controller\Factory\IndexControllerFactory;
use Application\Controller\IndexController;
use Application\Factory\ConfigFactory;
use Application\Repository\Factory\ImageRepositoryFactory;
use Application\Repository\Factory\URLRepositoryFactory;
use Application\Repository\ImageRepository;
use Application\Repository\ImageRepositoryInterface;
use Application\Repository\URLRepository;
use Application\Repository\URLRepositoryInterface;
use Application\Service\CrawlService;
use Application\Service\CrawlServiceInterface;
use Application\Service\Factory\CrawlServiceFactory;
use Application\Service\Factory\HammingDistanceServiceFactory;
use Application\Service\Factory\HashServiceFactory;
use Application\Service\Factory\HTMLServiceFactory;
use Application\Service\Factory\RabbitMQServiceFactory;
use Application\Service\HammingDistanceService;
use Application\Service\HammingDistanceServiceInterface;
use Application\Service\HashService;
use Application\Service\HashServiceInterface;
use Application\Service\HTMLService;
use Application\Service\HTMLServiceInterface;
use Application\Service\RabbitMQService;
use Application\Service\RabbitMQServiceInterface;
use Application\Service\URLParseService;
use Application\Service\URLParseServiceInterface;
use Application\Strategy\Factory\AbstractCrawlStrategyFactory;
use Application\ViewHelper\URLInputMessageHelper;
use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'console'         => require 'console.config.php',
    'router'          => [
        'routes' => [
            'home'    => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'url'     => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/url/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'url',
                    ],
                ],
            ],
            'search'  => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/search/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'search',
                    ],
                ],
            ],
            'results' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/results/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'results',
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            CrawlController::class => CrawlControllerFactory::class,
        ]
    ],
    'view_manager'    => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => [
            'layout/layout'             => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index'   => __DIR__ . '/../view/application/index/index.phtml',
            'application/index/search'  => __DIR__ . '/../view/application/index/search.phtml',
            'application/index/results' => __DIR__ . '/../view/application/index/results.phtml',
            'application/index/url'     => __DIR__ . '/../view/application/index/url.phtml',
            'error/404'                 => __DIR__ . '/../view/error/404.phtml',
            'error/index'               => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'aliases'            => [
            RabbitMQServiceInterface::class        => RabbitMQService::class,
            CrawlServiceInterface::class           => CrawlService::class,
            URLRepositoryInterface::class          => URLRepository::class,
            ImageRepositoryInterface::class        => ImageRepository::class,
            HTMLServiceInterface::class            => HTMLService::class,
            HashServiceInterface::class            => HashService::class,
            URLParseServiceInterface::class        => URLParseService::class,
            HammingDistanceServiceInterface::class => HammingDistanceService::class,
        ],
        'factories'          => [
            RabbitMQService::class            => RabbitMQServiceFactory::class,
            ApplicationConfigInterface::class => ConfigFactory::class,
            CrawlService::class               => CrawlServiceFactory::class,
            URLRepository::class              => URLRepositoryFactory::class,
            HTMLService::class                => HTMLServiceFactory::class,
            HashService::class                => HashServiceFactory::class,
            ImageRepository::class            => ImageRepositoryFactory::class,
            URLParseService::class            => InvokableFactory::class,
            HammingDistanceService::class     => HammingDistanceServiceFactory::class,
        ],
        'abstract_factories' => [
            AbstractCrawlStrategyFactory::class,
        ]
    ],
    'view_helpers'    => [
        'factories' => [
            URLInputMessageHelper::class => InvokableFactory::class
        ],
        'aliases'   => [
            'urlInputMessageHelper' => URLInputMessageHelper::class,
        ],
    ],
    'Application'     => [
        'rabbit_mq' => [
            'connection' => [
                'host'     => 'localhost',
                'port'     => 5672,
                'user'     => 'patrick.leonhardt',
                'password' => 'Check24.de',
            ],
            'queues'     => [
                'urlQueue'                => [
                    'passive'     => false,
                    'durable'     => false,
                    'exclusive'   => false,
                    'auto_delete' => false,
                ],
                'urlHighPriorityQueue'    => [
                    'passive'     => false,
                    'durable'     => false,
                    'exclusive'   => false,
                    'auto_delete' => false,
                ],
                'imgUrlQueue'             => [
                    'passive'     => false,
                    'durable'     => false,
                    'exclusive'   => false,
                    'auto_delete' => false,
                ],
                'imgUrlHighPriorityQueue' => [
                    'passive'     => false,
                    'durable'     => false,
                    'exclusive'   => false,
                    'auto_delete' => false,
                ],
                'imgQueue'                => [
                    'passive'     => false,
                    'durable'     => false,
                    'exclusive'   => false,
                    'auto_delete' => false,
                ],
            ],
        ],
    ],
];
