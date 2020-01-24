<?php


namespace Application\Service\Factory;

use Application\Service\HTMLService;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

class HTMLServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return HTMLService
     */
    function __invoke(ContainerInterface $container): HTMLService
    {
        return new HTMLService(new Client());
    }
}