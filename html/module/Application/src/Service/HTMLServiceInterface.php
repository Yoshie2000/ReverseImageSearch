<?php

namespace Application\Service;

interface HTMLServiceInterface
{

    /**
     * Returns the HTML for a given URL
     * @param string $url
     * @return string
     */
    public function getHTML(string $url): string;

    /**
     * Returns the hash for a given URL
     * @param string $url
     * @return string
     */
    public function getHash(string $url): string;

}