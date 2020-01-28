<?php

namespace Application\Service;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService implements RabbitMQServiceInterface
{

    /** @var AMQPStreamConnection */
    private $connection;
    /** @var AMQPChannel */
    private $channel;

    /**
     * RabbitMQService constructor.
     * @param AMQPStreamConnection $connection
     * @param AMQPChannel $channel
     */
    public function __construct(AMQPStreamConnection $connection, AMQPChannel $channel)
    {
        $this->connection = $connection;
        $this->channel = $channel;
    }

    /** ${@inheritDoc} */
    public function sendURL(string $url, string $queueName)
    {
        $url = trim($url);
        $channel = $this->channel;

        $channel->basic_publish(new AMQPMessage($url), "", $queueName . "Queue");
    }

    /** ${@inheritDoc} */
    public function getURL(string $queueName)
    {
        $channel = $this->channel;

        $data = $channel->basic_get($queueName . "Queue", true, null);
        while (is_null($data)) {
            $data = $channel->basic_get($queueName . "Queue", true, null);
        }
        return $data->body;
    }

    /** ${@inheritDoc} */
    public function getURLNoWait(string $queueName)
    {
        $channel = $this->channel;

        $data = $channel->basic_get($queueName . "Queue", true, null);
        return $data == null ? "" : $data->body;
    }

    public function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

}