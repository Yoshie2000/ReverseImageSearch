<?php

namespace Application\Strategy;

use Application\Service\HTMLServiceInterface;
use Application\Service\URLParseServiceInterface;
use DOMDocument;
use DOMElement;

abstract class AbstractCrawlStrategy
{

    /** @var DOMDocument */
    private $document;

    /** @var HTMLServiceInterface */
    private $htmlService;
    /** @var URLParseServiceInterface */
    private $urlParseService;

    /**
     * AbstractCrawlStrategy constructor.
     * @param DOMDocument $document
     * @param HTMLServiceInterface $htmlService
     * @param URLParseServiceInterface $urlParseService
     */
    public function __construct(
        DOMDocument $document,
        HTMLServiceInterface $htmlService,
        URLParseServiceInterface $urlParseService
    ) {
        $this->document = $document;
        $this->htmlService = $htmlService;
        $this->urlParseService = $urlParseService;
    }

    /**
     * Returns a list of all attributes with a given name on tags with a given name
     * @param $url string
     * @param $tagName string
     * @param $tagAttribute string
     * @param bool $correctLinks
     * @return array
     */
    public function find(string $url, string $tagName, string $tagAttribute, bool $correctLinks): array
    {
        $result = [];
        $document = $this->document;

        libxml_use_internal_errors(true);

        $document->loadHTML($this->htmlService->getHTML($url));

        $tags = $document->getElementsByTagName($tagName);
        /** @var DOMElement $tag */
        foreach ($tags as $tag) {
            $attribute = $tag->getAttribute($tagAttribute);
            if ($attribute === "") {
                $attribute = $tag->getAttribute("data-" . $tagAttribute);
            }
            if ($correctLinks) {
                $correctedLink = $this->urlParseService->url_to_absolute($url, $attribute);
                $result[] = $correctedLink;
            } else {
                $result[] = $attribute;
            }
        }

        return $result;
    }

}