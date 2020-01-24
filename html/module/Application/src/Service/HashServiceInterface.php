<?php


namespace Application\Service;


interface HashServiceInterface
{

    /**
     * Returns a hash of the image
     * @param string $imageUrl
     * @return string
     */
    public function hashImage(string $imageUrl):string;

}