<?php


namespace Application\Controller\Factory;


use Application\Controller\TestController;
use Psr\Container\ContainerInterface;

class TestControllerFactory
{
    public function __invoke(ContainerInterface $container): TestController
    {
        $routeMatch = $container->get('Application')->getMvcEvent()->getRouteMatch();

        return new TestController($routeMatch);
    }
}