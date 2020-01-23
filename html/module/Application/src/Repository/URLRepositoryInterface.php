<?php

namespace Application\Repository;

use Application\Model\URLModel;

interface URLRepositoryInterface
{

    /** @var int */
    public const URL_NOT_CHANGED = 0;
    /** @var int */
    public const URL_CHANGED = 1;
    /** @var int */
    public const URL_CREATED = 2;

    /**
     * Returns all the URLs from the database as URLModel objects in an array
     * @return array
     */
    public function getAllURLs(): array;

    /**
     * Returns the URLModel for a given URL
     * @param $url string
     * @return mixed
     */
    public function getURLInfo(string $url): URLModel;

    /**
     * Saves a given URL in the database
     * @param $url string
     * @return int Message code
     */
    public function saveURL(string $url): int;

    /**
     * Updates the content hash of the URL
     * @param $url
     * @return mixed
     */
    public function updateURLHash(string $url): void;

}