<?php


namespace Application\Service;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService implements RabbitMQServiceInterface
{

    /** @var AMQPStreamConnection */
    private $connection;

    /**
     * RabbitMQService constructor.
     * @param AMQPStreamConnection $connection
     */
    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    public function sendURL($url)
    {
        $channel = $this->connection->channel();

        $channel->queue_declare("urlQueue", false, false, false, false);
        $channel->basic_publish(new AMQPMessage($url), "", "urlQueue");

        $channel->close();
        $this->connection->close();
    }

    public function getURL($callback)
    {
        $channel = $this->connection->channel();

        $channel->queue_declare("urlQueue", false, false, false, false);
        $channel->basic_consume("urlQueue", "", false, true, true, false, $callback);
        $channel->wait();
    }
}