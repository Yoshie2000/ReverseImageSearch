<?php


namespace Application\Service;


interface CrawlServiceInterface
{

    public function crawl($url): array;

}