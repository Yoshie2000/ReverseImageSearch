<?php


namespace Application\Service;

class CrawlLinkStrategy extends AbstractCrawlStrategy implements CrawlServiceInterface
{

    public function crawl($url): array
    {
        return $this->correctLinks($url, $this->find($url, "a", "href"));
    }

}