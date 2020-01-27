<?php

namespace Application\Service;

interface URLParseServiceInterface
{
    /**
     * Combine a base URL and a relative URL to produce a new
     * absolute URL.  The base URL is often the URL of a page,
     * and the relative URL is a URL embedded on that page.
     *
     * This function implements the "absolutize" algorithm from
     * the RFC3986 specification for URLs.
     *
     * This function supports multi-byte characters with the UTF-8 encoding,
     * per the URL specification.
     *
     * Parameters:
     *    baseUrl        the absolute base URL.
     *
     *    url        the relative URL to convert.
     *
     * Return values:
     *    An absolute URL that combines parts of the base and relative
     *    URLs, or FALSE if the base URL is not absolute or if either
     *    URL cannot be parsed.
     */
    public function url_to_absolute($baseUrl, $relativeUrl);
}