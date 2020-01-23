<?php

namespace Application\Service\Factory;

use Application\ApplicationConfigInterface;
use Application\Service\RabbitMQService;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

class RabbitMQServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return RabbitMQService
     */
    function __invoke(ContainerInterface $container): RabbitMQService
    {
        $config = $container->get(ApplicationConfigInterface::class);

        $connectionConfig = $config->rabbit_mq->connection;
        $queueConfig = $config->rabbit_mq->queues->toArray();

        $connection = new AMQPStreamConnection($connectionConfig->host, $connectionConfig->port,
            $connectionConfig->user, $connectionConfig->password);
        $channel = $connection->channel();

        foreach ($queueConfig as $queueName => $queueValues) {
            $channel->queue_declare($queueName, $queueValues["passive"], $queueValues["durable"],
                $queueValues["exclusive"], $queueValues["auto_delete"]);
        }

        return new RabbitMQService($connection, $channel);
    }
}