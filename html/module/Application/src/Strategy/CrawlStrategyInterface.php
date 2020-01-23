<?php

namespace Application\Strategy;

interface CrawlStrategyInterface
{

    /**
     * Used to crawl a URL
     * @param $url string
     * @return array
     */
    public function crawl(string $url): array;

}