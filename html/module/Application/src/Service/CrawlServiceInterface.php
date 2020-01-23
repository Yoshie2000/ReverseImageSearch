<?php

namespace Application\Service;

interface CrawlServiceInterface
{

    /**
     * Executes a crawl on the given URL with the given mode (e.g. images, links)
     * @param $url string
     * @param $mode string
     * @return mixed
     */
    public function executeCrawl(string $url, string $mode);

    /**
     * Executes an image crawl on the given URL
     * @param $url string
     * @return mixed
     */
    public function executeImageCrawl(string $url);

    /**
     * Executes a link crawl on the given URL
     * @param $url string
     * @return mixed
     */
    public function executeLinkCrawl(string $url);

}