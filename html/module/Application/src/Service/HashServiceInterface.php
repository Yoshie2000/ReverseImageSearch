<?php


namespace Application\Service;


interface HashServiceInterface
{

    /**
     * Returns a hash of the image
     * @param string $imageUrl
     * @return string
     */
    public function hashImage(string $imageUrl): string;

    /**
     * Returns a hash from the image (that is on the current machine)
     * @param string $imageUrl
     * @return string
     */
    public function hashImageLocal(string $imageUrl): string;

}