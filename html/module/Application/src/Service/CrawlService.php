<?php

namespace Application\Service;

use Application\Model\URLModel;
use Application\Repository\ImageRepositoryInterface;
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
    /** @var ImageRepositoryInterface */
    private $imageRepository;

    /** @var RabbitMQServiceInterface */
    private $rabbitmqService;
    /** @var HashServiceInterface */
    private $hashService;

    /**
     * CrawlService constructor.
     * @param CrawlImageStrategy $imageStrategy
     * @param CrawlLinkStrategy $linkStrategy
     * @param URLRepositoryInterface $urlRepository
     * @param ImageRepositoryInterface $imageRepository
     * @param RabbitMQServiceInterface $rabbitmqService
     * @param HashServiceInterface $hashService
     */
    public function __construct(
        CrawlImageStrategy $imageStrategy,
        CrawlLinkStrategy $linkStrategy,
        URLRepositoryInterface $urlRepository,
        ImageRepositoryInterface $imageRepository,
        RabbitMQServiceInterface $rabbitmqService,
        HashServiceInterface $hashService
    ) {
        $this->imageStrategy = $imageStrategy;
        $this->linkStrategy = $linkStrategy;
        $this->urlRepository = $urlRepository;
        $this->imageRepository = $imageRepository;
        $this->rabbitmqService = $rabbitmqService;
        $this->hashService = $hashService;
    }

    /** ${@inheritDoc} */
    public function executeCrawl(string $url, string $mode)
    {
        echo "Execute crawl: " . $url . " (" . $mode . ")" . PHP_EOL;

        switch ($mode) {
            case "url":
                $this->executeLinkCrawl($url, false);
                break;
            case "imgUrl":
                $this->executeImageUrlCrawl($url, false);
                break;
            case "urlHighPriority":
                $this->executeLinkCrawl($url, true);
                break;
            case "imgUrlHighPriority":
                $this->executeImageUrlCrawl($url, true);
                break;
            default:
                break;
        }
    }

    /** ${@inheritDoc} */
    public function executeImageUrlCrawl(string $url, bool $priority)
    {
        // Remove the number
        if (is_numeric($url[0])) {
            $url = substr($url, 1);
        }

        /** @var URLModel $urlModel */
        $urlModel = $this->urlRepository->getURLInfo($url);

        if (is_null($urlModel)) {
            return;
        }

        $images = $this->imageStrategy->crawl($url);
        foreach ($images as $image) {
            $hash = $this->hashService->hashImage($image);
            if ($hash !== "" && $hash !== "0") {
                $messageCode = $this->imageRepository->saveImage($urlModel->getId(), $image, $hash);
                if ($messageCode !== ImageRepositoryInterface::IMAGE_EXISTS) {
                    $this->rabbitmqService->sendURL($hash, "img");
                }
            }
            echo $image . "    " . $hash . PHP_EOL;
        }
        echo PHP_EOL;
    }

    /** ${@inheritDoc} */
    public function executeLinkCrawl(string $url, bool $priority)
    {
        $urlIndex = 0;
        // Get and remove the number
        if ($priority) {
            $urlIndex = intval($url[0]);
            $url = substr($url, 1);
        }

        $highPriority = $priority && $urlIndex < 1;
        $priorityQueue = $highPriority ? "HighPriority" : "";

        $links = $this->linkStrategy->crawl($url);
        foreach ($links as $link) {
            // Saves the URL in the database and in the urlQueue and in the imgUrlQueue
            $messageCode = $this->urlRepository->saveURL($link);
            if ($messageCode !== URLRepositoryInterface::URL_NOT_CHANGED) {
                $priorityLink = $highPriority ? ($urlIndex + 1) . $link : $link;

                $this->rabbitmqService->sendURL($priorityLink, "url" . $priorityQueue);
                $this->rabbitmqService->sendURL($priorityLink, "imgUrl" . $priorityQueue);
            }
            echo $link . PHP_EOL;
        }
        echo PHP_EOL;
    }

}