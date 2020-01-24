<?php

namespace Application\Service;

interface RabbitMQServiceInterface
{

    /**
     * Sends a given URL to a given queue
     * @param $url string
     * @param string $queueName
     * @return mixed
     */
    public function sendURL(string $url, string $queueName);

    /**
     * Waits for a URL from the given queue. The callback function will be called automatically.
     * @param $queueName
     * @return mixed
     */
    public function getURL(string $queueName);

}