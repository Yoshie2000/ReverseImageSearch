<?php

namespace Application\Service;

interface RabbitMQServiceInterface
{

    /**
     * Sends a given URL to RabbitMQ
     * @param $url string
     * @return mixed
     */
    public function sendURL(string $url);

    /**
     * Waits for a URL from RabbitMQ. The callback function will be called automatically.
     * @param $callback
     * @return mixed
     */
    public function getURL($callback);

}