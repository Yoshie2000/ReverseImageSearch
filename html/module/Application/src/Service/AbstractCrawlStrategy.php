<?php


namespace Application\Service;


use DOMDocument;
use DOMElement;

abstract class AbstractCrawlStrategy
{

    /** @var DOMDocument */
    private $document;

    /** @var URLServiceInterface */
    private $urlService;

    /**
     * AbstractCrawlStrategy constructor.
     * @param DOMDocument $document
     * @param URLServiceInterface $urlService
     */
    public function __construct(DOMDocument $document, URLServiceInterface $urlService)
    {
        $this->document = $document;
        $this->urlService = $urlService;
    }

    public function correctLinks($url, $links):array {
        foreach ($links as &$link) {
            if (!(substr(strtolower($link), 0, 7) === "http://" || substr(strtolower($link), 0, 8) === "https://")) {
                $link = $url . "/" . $link;
            }
        }
        return $links;
    }

    public function find($url, $tagName, $tagAttribute): array
    {
        $result = [];
        $document = $this->document;

        libxml_use_internal_errors(true);

        $document->loadHTML($this->urlService->getHTML($url));

        $tags = $document->getElementsByTagName($tagName);
        /** @var DOMElement $link */
        foreach ($tags as $tag) {
            $result[] = $tag->getAttribute($tagAttribute);
        }

        return $result;
    }

}