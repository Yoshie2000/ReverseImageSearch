<?php


namespace Application\Service;

class CrawlImageStrategy extends AbstractCrawlStrategy implements CrawlServiceInterface
{

    public function crawl($url): array
    {
        return $this->correctLinks($url, $this->find($url, "img", "src"));
    }

}