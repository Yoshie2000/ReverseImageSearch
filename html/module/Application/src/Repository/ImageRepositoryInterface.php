<?php


namespace Application\Repository;


interface ImageRepositoryInterface
{

    /**
     * Saves an image to the database
     * @param int $urlID
     * @param string $imageURL
     * @param string $imageHash
     * @return mixed
     */
    public function saveImage(int $urlID, string $imageURL, string $imageHash);

    /**
     * Returns the ImageModel of the image with the given hash
     * @param string $imageHash
     * @return mixed
     */
    public function getImageModel(string $imageHash);

    /**
     * Returns all ImageModels in the database
     * @return array
     */
    public function getAllImages():array;

}