<?php

namespace Application\Console;

use Application\Service\CrawlService;
use Application\Service\RabbitMQService;
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

        // Take a URL from the specified queue and crawl it
        $mode = $request->getParam("mode");

        $url = trim($this->rabbitmqService->getURLNoWait($mode . "HighPriority"));
        if (strlen($url) == 0) {
            $url = trim($this->rabbitmqService->getURL($mode));
        } else {
            $mode .= "HighPriority";
        }

        $this->crawlService->executeCrawl($url, $mode);
    }

}