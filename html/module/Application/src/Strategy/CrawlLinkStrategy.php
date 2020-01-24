<?php

namespace Application\Strategy;

class CrawlLinkStrategy extends AbstractCrawlStrategy implements CrawlStrategyInterface
{

    /**
     * Finds all the links in the URL
     * @param $url string
     * @return array
     */
    public function crawl(string $url): array
    {
        return $this->find($url, "a", "href", true);
    }

}