<?php


namespace Application\Service\Factory;


use Application\Service\RabbitMQService;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

class RabbitMQServiceFactory
{
    function __invoke(ContainerInterface $container)
    {
        return new RabbitMQService(new AMQPStreamConnection('localhost', 5672, 'patrick.leonhardt', 'Check24.de'));
    }
}