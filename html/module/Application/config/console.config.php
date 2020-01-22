<?php
return [
    'router' => [
        'routes' => [
            'crawlurl' => [
                'options' => [
                    'route'    => 'crawlurl (images|links):mode <url>',
                    'defaults' => [
                        'controller' => \Application\Console\CrawlController::class,
                        'action'     => 'crawlurl',
                    ],
                ],
            ],
            'crawl'    => [
                'options' => [
                    'route'    => 'crawl (images|links):mode',
                    'defaults' => [
                        'controller' => \Application\Console\CrawlController::class,
                        'action'     => 'crawl',
                    ],
                ],
            ],
        ],
    ],
];