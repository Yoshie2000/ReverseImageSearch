<?php

use Application\Console\CrawlController;

// crawl url:   Takes a URL from urlQueue, finds all links and saves them in urlQueue and imgUrlQueue
// craw imgUrl: Takes a URL from imgUrlQueue, finds all images, hashes them and saves them in imgQueue

return [
    'router' => [
        'routes' => [
            'crawl'    => [
                'options' => [
                    'route'    => 'crawl (imgUrl|url):mode',
                    'defaults' => [
                        'controller' => CrawlController::class,
                        'action'     => 'crawl',
                    ],
                ],
            ]
        ],
    ],
];