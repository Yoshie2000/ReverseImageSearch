<?php


namespace Application\Console\Factory;

use Application\Console\CrawlController;
use Application\Service\CrawlImageStrategy;
use Application\Service\CrawlLinkStrategy;
use Application\Service\RabbitMQServiceInterface;
use Application\Service\URLServiceInterface;
use Psr\Container\ContainerInterface;

class CrawlControllerFactory
{

    public function __invoke(ContainerInterface $container): CrawlController
    {
        return new CrawlController($container->get(CrawlLinkStrategy::class),
            $container->get(CrawlImageStrategy::class), $container->get(URLServiceInterface::class), $container->get(RabbitMQServiceInterface::class));
    }

}