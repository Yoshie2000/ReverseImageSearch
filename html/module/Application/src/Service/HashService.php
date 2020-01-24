<?php


namespace Application\Service;


use Exception;
use Jenssegers\ImageHash\ImageHash;

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
            echo $e->getMessage() . PHP_EOL;
            echo $e->getTraceAsString() . PHP_EOL;
            return "";
        }
    }
}