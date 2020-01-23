<?php

use Application\Console\CrawlController;

return [
    'router' => [
        'routes' => [
            'crawlurl' => [
                'options' => [
                    'route'    => 'crawlurl (images|links):mode <url>',
                    'defaults' => [
                        'controller' => CrawlController::class,
                        'action'     => 'crawlurl',
                    ],
                ],
            ],
            'crawl'    => [
                'options' => [
                    'route'    => 'crawl (images|links):mode',
                    'defaults' => [
                        'controller' => CrawlController::class,
                        'action'     => 'crawl',
                    ],
                ],
            ],
        ],
    ],
];