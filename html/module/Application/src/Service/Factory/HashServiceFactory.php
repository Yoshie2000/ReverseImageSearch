<?php


namespace Application\Service\Factory;


use Application\Service\HashService;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Psr\Container\ContainerInterface;

class HashServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return HashService
     */
    function __invoke(ContainerInterface $container): HashService
    {
        return new HashService(new ImageHash(new DifferenceHash()));
    }
}