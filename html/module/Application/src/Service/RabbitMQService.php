<?php

namespace Application\Service;

use ErrorException;
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
    public function sendURL(string $url)
    {
        $channel = $this->channel;

        $channel->basic_publish(new AMQPMessage($url), "", "urlQueue");
    }

    /** ${@inheritDoc} */
    public function getURL($callback)
    {
        $channel = $this->channel;

        $channel->basic_consume("urlQueue", "", false, false, true, false, $callback);
        try {
            $channel->wait();
        } catch (ErrorException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

}