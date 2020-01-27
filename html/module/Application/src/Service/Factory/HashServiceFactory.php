<?php


namespace Application\Service\Factory;


use Application\Service\HashService;
use Application\Service\HTMLServiceInterface;
use Imagick;
use ImagickException;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Psr\Container\ContainerInterface;

class HashServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return HashService
     * @throws ImagickException
     */
    function __invoke(ContainerInterface $container): HashService
    {
        return new HashService(
            new ImageHash(new DifferenceHash()),
            $container->get(HTMLServiceInterface::class),
            new Imagick()
        );
    }
}