<?php

namespace Application\Strategy;

use Application\Service\HTMLServiceInterface;
use DOMDocument;
use DOMElement;

abstract class AbstractCrawlStrategy
{

    /** @var DOMDocument */
    private $document;

    /** @var HTMLServiceInterface */
    private $htmlService;

    /**
     * AbstractCrawlStrategy constructor.
     * @param DOMDocument $document
     * @param HTMLServiceInterface $htmlService
     */
    public function __construct(DOMDocument $document, HTMLServiceInterface $htmlService)
    {
        $this->document = $document;
        $this->htmlService = $htmlService;
    }

    /**
     * Corrects all the links in the given array (e. g. /about.html will become $url/about.html
     * @param $url string
     * @param $links array
     * @return array
     */
    public function correctLinks(string $url, array $links): array
    {
        foreach ($links as &$link) {
            if (!(substr(strtolower($link), 0, 7) === "http://" || substr(strtolower($link), 0, 8) === "https://")) {
                $link = $url . "/" . $link;
            }
        }
        return $links;
    }

    /**
     * Returns a list of all attributes with a given name on tags with a given name
     * @param $url string
     * @param $tagName string
     * @param $tagAttribute string
     * @return array
     */
    public function find(string $url, string $tagName, string $tagAttribute): array
    {
        $result = [];
        $document = $this->document;

        libxml_use_internal_errors(true);

        $document->loadHTML($this->htmlService->getHTML($url));

        $tags = $document->getElementsByTagName($tagName);
        /** @var DOMElement $link */
        foreach ($tags as $tag) {
            $result[] = $tag->getAttribute($tagAttribute);
        }

        return $result;
    }

}