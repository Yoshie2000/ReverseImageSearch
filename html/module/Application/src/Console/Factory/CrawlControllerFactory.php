<?php


namespace Application\Console\Factory;

use Application\Console\CrawlController;
use Application\Service\CrawlServiceInterface;
use Application\Service\RabbitMQServiceInterface;
use Psr\Container\ContainerInterface;

class CrawlControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return CrawlController
     */
    public function __invoke(ContainerInterface $container): CrawlController
    {
        return new CrawlController(
            $container->get(CrawlServiceInterface::class),
            $container->get(RabbitMQServiceInterface::class)
        );
    }

}