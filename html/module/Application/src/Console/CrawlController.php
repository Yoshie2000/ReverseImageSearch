<?php

namespace Application\Console;

use Application\Service\CrawlService;
use Application\Service\RabbitMQService;
use PhpAmqpLib\Message\AMQPMessage;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\View\Exception\RuntimeException;

class CrawlController extends AbstractConsoleController
{

    /** @var CrawlService */
    private $crawlService;
    /** @var RabbitMQService */
    private $rabbitmqService;

    /**
     * CrawlController constructor.
     * @param CrawlService $crawlService
     * @param RabbitMQService $rabbitmqService
     */
    public function __construct(CrawlService $crawlService, RabbitMQService $rabbitmqService)
    {
        $this->crawlService = $crawlService;
        $this->rabbitmqService = $rabbitmqService;
    }

    /**
     * Used to crawl a specific URL
     */
    public function crawlurlAction()
    {
        $request = $this->getRequest();

        if (!$request instanceof ConsoleRequest) {
            throw new RuntimeException(
                'You can only use this action from a console!'
            );
        }

        $mode = $request->getParam("mode");
        $url = $request->getParam("url");
        $this->crawlService->executeCrawl($url, $mode);
    }

    /**
     * Used to take a URL from RabbitMQ and crawl it
     */
    public function crawlAction()
    {
        $request = $this->getRequest();

        if (!$request instanceof ConsoleRequest) {
            throw new RuntimeException(
                'You can only use this action from a console!'
            );
        }

        $mode = $request->getParam("mode");
        $this->rabbitmqService->getURL(function (AMQPMessage $data) use ($mode) {
            $url = $data->body;
            $this->crawlService->executeCrawl($url, $mode);
            return $this->rabbitmqService->getChannel()->basic_ack($data->getDeliveryTag());
        });
    }

}