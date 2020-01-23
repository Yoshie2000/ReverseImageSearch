<?php

namespace Application\Service;

use Application\Repository\URLRepositoryInterface;
use Application\Strategy\CrawlImageStrategy;
use Application\Strategy\CrawlLinkStrategy;

class CrawlService implements CrawlServiceInterface
{
    /** @var CrawlImageStrategy */
    private $imageStrategy;
    /** @var CrawlLinkStrategy */
    private $linkStrategy;

    /** @var URLRepositoryInterface */
    private $urlRepository;
    /** @var RabbitMQServiceInterface */
    private $rabbitmqService;

    /**
     * CrawlService constructor.
     * @param CrawlImageStrategy $imageStrategy
     * @param CrawlLinkStrategy $linkStrategy
     * @param URLRepositoryInterface $urlRepository
     * @param RabbitMQServiceInterface $rabbitmqService
     */
    public function __construct(
        CrawlImageStrategy $imageStrategy,
        CrawlLinkStrategy $linkStrategy,
        URLRepositoryInterface $urlRepository,
        RabbitMQServiceInterface $rabbitmqService
    ) {
        $this->imageStrategy = $imageStrategy;
        $this->linkStrategy = $linkStrategy;
        $this->urlRepository = $urlRepository;
        $this->rabbitmqService = $rabbitmqService;
    }

    /** ${@inheritDoc} */
    public function executeCrawl(string $url, string $mode)
    {
        echo "Execute crawl", PHP_EOL;

        switch ($mode) {
            case "links":
                $this->executeLinkCrawl($url);
                break;
            case "images":
                $this->executeImageCrawl($url);
                break;
            default:
                break;
        }
    }

    /** ${@inheritDoc} */
    public function executeImageCrawl(string $url)
    {
        $images = $this->imageStrategy->crawl($url);
        foreach ($images as $image) {
            echo $image . PHP_EOL;
        }
        echo PHP_EOL;
    }

    /** ${@inheritDoc} */
    public function executeLinkCrawl(string $url)
    {
        $links = $this->linkStrategy->crawl($url);
        foreach ($links as $link) {
            $this->urlRepository->saveURL($link);
            $this->rabbitmqService->sendURL($link);
            echo $link . PHP_EOL;
        }
        echo PHP_EOL;
    }

}