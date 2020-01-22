<?php


namespace Application\Service\Factory;

use Application\Service\CrawlHashStrategy;
use Application\Service\URLService;
use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

class URLServiceFactory
{
    function __invoke(ContainerInterface $container)
    {
        return new URLService($container->get(AdapterInterface::class));
    }
}