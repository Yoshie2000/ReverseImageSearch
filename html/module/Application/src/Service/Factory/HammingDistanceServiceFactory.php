<?php


namespace Application\Service\Factory;


use Application\Service\HammingDistanceService;
use Psr\Container\ContainerInterface;

class HammingDistanceServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return HammingDistanceService
     */
    function __invoke(ContainerInterface $container): HammingDistanceService
    {
        return new HammingDistanceService();
    }
}