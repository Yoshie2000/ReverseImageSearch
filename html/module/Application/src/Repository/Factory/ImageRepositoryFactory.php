<?php


namespace Application\Repository\Factory;


use Application\Repository\ImageRepository;
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
            $container->get(AdapterInterface::class)
        );
    }
}