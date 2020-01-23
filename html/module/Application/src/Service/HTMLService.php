<?php

namespace Application\Service;

class HTMLService implements HTMLServiceInterface
{

    /** ${@inheritDoc} */
    public function getHTML(string $url): string
    {
        return utf8_encode(file_get_contents($url));
    }

    /** ${@inheritDoc} */
    public function getHash(string $url): string
    {
        return hash("md5", $this->getHTML($url));
    }
}