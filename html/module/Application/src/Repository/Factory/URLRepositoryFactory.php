<?php


namespace Application\Repository\Factory;

use Application\Repository\URLRepository;
use Application\Service\HTMLServiceInterface;
use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

class URLRepositoryFactory
{
    /**
     * @param ContainerInterface $container
     * @return URLRepository
     */
    function __invoke(ContainerInterface $container): URLRepository
    {
        return new URLRepository(
            $container->get(AdapterInterface::class),
            $container->get(HTMLServiceInterface::class)
        );
    }
}