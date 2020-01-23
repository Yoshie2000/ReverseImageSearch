<?php

namespace Application\Factory;

use Psr\Container\ContainerInterface;
use Zend\Config\Config;

class ConfigFactory
{

    function __invoke(ContainerInterface $container): Config
    {
        $config = new Config($container->get("Config"));
        return $config->Application;
    }

}