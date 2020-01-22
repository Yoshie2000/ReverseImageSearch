<?php


namespace Application\Service;


interface RabbitMQServiceInterface
{

    public function sendURL($url);

    public function getURL($callback);

}