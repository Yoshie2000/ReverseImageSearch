<?php

namespace Application\Controller\Factory;

use Application\Controller\IndexController;
use Application\Repository\ImageRepositoryInterface;
use Application\Repository\URLRepositoryInterface;
use Application\Service\RabbitMQServiceInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IndexController
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator): IndexController
    {
        return new IndexController(
            $serviceLocator->get(URLRepositoryInterface::class),
            $serviceLocator->get(ImageRepositoryInterface::class),
            $serviceLocator->get(RabbitMQServiceInterface::class)
        );
    }
}