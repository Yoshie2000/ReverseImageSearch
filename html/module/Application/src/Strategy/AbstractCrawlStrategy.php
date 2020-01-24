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

    function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    function endsWith($string, $endString)
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }

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
     * Corrects a given link
     * @param string $url
     * @param string $link
     * @return string
     */
    public function correctLink(string $url, string $link): string
    {
        $parsedUrl = parse_url($url);

        if (!$this->startsWith($link, "http://") && !$this->startsWith($link, "https://")) {
            return $parsedUrl["scheme"] . "://" . $parsedUrl["host"] . "/" . $link;
        }
        return $link;
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
                $correctedLink = $this->correctLink($url, $attribute);
                $result[] = $correctedLink;
            } else {
                $result[] = $attribute;
            }
        }

        return $result;
    }

}