<?php


namespace Application\Repository\Factory;


use Application\Repository\ImageRepository;
use Application\Service\HammingDistanceServiceInterface;
use Application\Service\HashServiceInterface;
use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

class ImageRepositoryFactory
{
    /**
     * @param ContainerInterface $container
     * @return ImageRepository
     */
    function __invoke(ContainerInterface $container): ImageRepository
    {
        return new ImageRepository(
            $container->get(AdapterInterface::class),
            $container->get(HashServiceInterface::class),
            $container->get(HammingDistanceServiceInterface::class)
        );
    }
}