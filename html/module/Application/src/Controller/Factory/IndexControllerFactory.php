<?php


namespace Application\Controller\Factory;


use Application\Controller\IndexController;
use Application\Service\RabbitMQServiceInterface;
use Application\Service\URLServiceInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory
{
    public function __invoke(ServiceLocatorInterface $serviceLocator): IndexController
    {
        $postService = $serviceLocator->get(URLServiceInterface::class);
        return new IndexController($postService, $serviceLocator->get(RabbitMQServiceInterface::class));
    }
}