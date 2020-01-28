<?php


namespace Application\Repository;


interface ImageRepositoryInterface
{

    /** @var int */
    public const IMAGE_EXISTS = 0;
    /** @var int */
    public const IMAGE_DOESNT_EXIST = 1;

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
    public function getAllImages(): array;

    /**
     * Returns all the images within a specific hamming distance to the given image
     * @param string $imagePath
     * @param int $distance
     * @return array
     */
    public function getImagesInDistance(string $imagePath, int $distance): array;

}