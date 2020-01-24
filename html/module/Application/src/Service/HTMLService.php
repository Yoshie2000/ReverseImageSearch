<?php

namespace Application\Service;

use Exception;
use GuzzleHttp\Client;

class HTMLService implements HTMLServiceInterface
{

    /** @var Client */
    private $client;

    /**
     * HTMLService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /** ${@inheritDoc} */
    public function getHTML(string $url): string
    {
        try {
            $client = $this->client;
            $response = $client->request("GET", $url);

            if ($response->getStatusCode() !== 200) {
                return "";
            }
            $html = $response->getBody();
            return utf8_encode($html);
        } catch (Exception $e) {
            return "";
        }
    }

    /** ${@inheritDoc} */
    public function getHash(string $url): string
    {
        $html = $this->getHTML($url);
        return hash("md5", $html);
    }
}