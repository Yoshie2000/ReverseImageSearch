<?php


namespace Application\Model;


interface URLModelInterface
{

    /**
     * Returns the ID/primary key of the crawled website
     * @return int
     */
    public function getId(): int;

    /**
     * Returns the URL of the crawled website
     * @return string
     */
    public function getUrl(): string;

    /**
     * Returns the md5 hash of the content of the crawled website
     * @return string
     */
    public function getContentHash(): string;

    /**
     * Returns how many images were on the crawled website
     * @return int
     */
    public function getImageCount(): int;

}