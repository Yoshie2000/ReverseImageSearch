<?php

namespace Application\Strategy\Factory;

use Application\Service\HTMLServiceInterface;
use Application\Service\URLParseServiceInterface;
use Application\Strategy\CrawlImageStrategy;
use Application\Strategy\CrawlLinkStrategy;
use DOMDocument;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class AbstractCrawlStrategyFactory implements AbstractFactoryInterface
{

    /**
     * Can the factory create an instance for the service?
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        return $requestedName === CrawlImageStrategy::class || $requestedName === CrawlLinkStrategy::class;
    }

    /**
     * Create an object
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            new DOMDocument(),
            $container->get(HTMLServiceInterface::class),
            $container->get(URLParseServiceInterface::class)
        );
    }
}