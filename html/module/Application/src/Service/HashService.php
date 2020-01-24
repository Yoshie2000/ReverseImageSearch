<?php


namespace Application\Service;


use Exception;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class HashService implements HashServiceInterface
{

    /** @var ImageHash */
    private $hasher;

    /**
     * HashService constructor.
     * @param ImageHash $hasher
     */
    public function __construct(ImageHash $hasher)
    {
        $this->hasher = $hasher;
    }

    /** ${@inheritDoc} */
    public function hashImage(string $imageUrl): string
    {
        try {
            $hash = $this->hasher->hash($imageUrl);
            return $hash->toHex();
        } catch (Exception $e) {
            return "";
        }
    }
}