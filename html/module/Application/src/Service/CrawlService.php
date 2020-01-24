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
    /** @var HashServiceInterface */
    private $hashService;

    /**
     * CrawlService constructor.
     * @param CrawlImageStrategy $imageStrategy
     * @param CrawlLinkStrategy $linkStrategy
     * @param URLRepositoryInterface $urlRepository
     * @param RabbitMQServiceInterface $rabbitmqService
     * @param HashServiceInterface $hashService
     */
    public function __construct(
        CrawlImageStrategy $imageStrategy,
        CrawlLinkStrategy $linkStrategy,
        URLRepositoryInterface $urlRepository,
        RabbitMQServiceInterface $rabbitmqService,
        HashServiceInterface $hashService
    ) {
        $this->imageStrategy = $imageStrategy;
        $this->linkStrategy = $linkStrategy;
        $this->urlRepository = $urlRepository;
        $this->rabbitmqService = $rabbitmqService;
        $this->hashService = $hashService;
    }

    /** ${@inheritDoc} */
    public function executeCrawl(string $url, string $mode)
    {
        echo "Execute crawl: " . $url . " (" . $mode . ")" . PHP_EOL;

        switch ($mode) {
            case "url":
                $this->executeLinkCrawl($url);
                break;
            case "imgUrl":
                $this->executeImageUrlCrawl($url);
                break;
            default:
                break;
        }
    }

    /** ${@inheritDoc} */
    public function executeImageUrlCrawl(string $url)
    {
        $images = $this->imageStrategy->crawl($url);
        foreach ($images as $image) {
            $hash = $this->hashService->hashImage($image);
            if ($hash !== "") {
                $this->rabbitmqService->sendURL($hash, "img");
            }
            echo $hash . PHP_EOL;
        }
        echo PHP_EOL;
    }

    /** ${@inheritDoc} */
    public function executeLinkCrawl(string $url)
    {
        $links = $this->linkStrategy->crawl($url);
        foreach ($links as $link) {
            // Saves the URL in the database and in the urlQueue and in the imgUrlQueue
            $messageCode = $this->urlRepository->saveURL($link);
            if ($messageCode !== URLRepositoryInterface::URL_NOT_CHANGED) {
                $this->rabbitmqService->sendURL($link, "url");
                $this->rabbitmqService->sendURL($link, "imgUrl");
            }
            echo $link . PHP_EOL;
        }
        echo PHP_EOL;
    }

}