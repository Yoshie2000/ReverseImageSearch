<?php

namespace Application\Service\Factory;

use Application\Repository\URLRepositoryInterface;
use Application\Service\CrawlService;
use Application\Service\RabbitMQServiceInterface;
use Application\Strategy\CrawlImageStrategy;
use Application\Strategy\CrawlLinkStrategy;
use Psr\Container\ContainerInterface;

class CrawlServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return CrawlService
     */
    function __invoke(ContainerInterface $container): CrawlService
    {
        return new CrawlService(
            $container->get(CrawlImageStrategy::class),
            $container->get(CrawlLinkStrategy::class),
            $container->get(URLRepositoryInterface::class),
            $container->get(RabbitMQServiceInterface::class)
        );
    }
}