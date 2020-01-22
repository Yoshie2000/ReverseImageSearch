<?php

namespace Application\Console;

use Application\Service\CrawlImageStrategy;
use Application\Service\CrawlLinkStrategy;
use Application\Service\RabbitMQServiceInterface;
use Application\Service\URLServiceInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\View\Exception\RuntimeException;

class CrawlController extends AbstractConsoleController
{

    /** @var CrawlLinkStrategy */
    private $linkStrategy;
    /** @var CrawlImageStrategy */
    private $imageStrategy;

    /** @var URLServiceInterface */
    private $urlService;
    /** @var RabbitMQServiceInterface */
    private $rabbitmqService;

    /**
     * CrawlController constructor.
     * @param CrawlLinkStrategy $linkStrategy
     * @param CrawlImageStrategy $imageStrategy
     * @param URLServiceInterface $urlService
     * @param RabbitMQServiceInterface $rabbitmqService
     */
    public function __construct(
        CrawlLinkStrategy $linkStrategy,
        CrawlImageStrategy $imageStrategy,
        URLServiceInterface $urlService,
        RabbitMQServiceInterface $rabbitmqService
    ) {
        $this->linkStrategy = $linkStrategy;
        $this->imageStrategy = $imageStrategy;
        $this->urlService = $urlService;
        $this->rabbitmqService = $rabbitmqService;
    }

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
        $this->executeCrawl($url, $mode);
    }

    public function crawlAction()
    {
        $request = $this->getRequest();
        $rabbitmqService = $this->rabbitmqService;

        if (!$request instanceof ConsoleRequest) {
            throw new RuntimeException(
                'You can only use this action from a console!'
            );
        }

        $mode = $request->getParam("mode");
        $rabbitmqService->getURL(function (AMQPMessage $data) use ($mode) {
            $url = $data->body;
            $this->executeCrawl($url, $mode);
        });
    }

    public function executeCrawl($url, $mode)
    {
        echo "execute crawl", PHP_EOL;
        $urlService = $this->urlService;
        $rabbitmqService = $this->rabbitmqService;

        switch ($mode) {
            case "links":
                $links = $this->linkStrategy->crawl($url);
                foreach ($links as $link) {
                    $urlService->saveURL($link);
                    $rabbitmqService->sendURL($link);
                    echo $link . PHP_EOL;
                }
                echo PHP_EOL;
                break;
            case "images":
                $images = $this->imageStrategy->crawl($url);
                foreach ($images as $image) {
                    echo $image . PHP_EOL;
                }
                echo PHP_EOL;
                break;
            default:
                break;
        }
    }

}