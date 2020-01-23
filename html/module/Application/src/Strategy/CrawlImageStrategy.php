<?php

namespace Application\Strategy;

class CrawlImageStrategy extends AbstractCrawlStrategy implements CrawlStrategyInterface
{

    /**
     * Returns all the image links in the URL
     * @param $url string
     * @return array
     */
    public function crawl(string $url): array
    {
        return $this->correctLinks($url, $this->find($url, "img", "src"));
    }

}