<?php


namespace Application\Service;


use Exception;
use Imagick;
use Jenssegers\ImageHash\ImageHash;

class HashService implements HashServiceInterface
{

    /** @var ImageHash */
    private $hasher;
    /** @var HTMLServiceInterface */
    private $htmlService;
    /** @var Imagick */
    private $imagick;

    /**
     * HashService constructor.
     * @param ImageHash $hasher
     * @param HTMLServiceInterface $htmlService
     * @param Imagick $imagick
     */
    public function __construct(ImageHash $hasher, HTMLServiceInterface $htmlService, Imagick $imagick)
    {
        $this->hasher = $hasher;
        $this->htmlService = $htmlService;
        $this->imagick = $imagick;
    }

    /** ${@inheritDoc} */
    public function hashImage(string $imageUrl): string
    {
        try {

            $isSvg = false;

            if (substr($imageUrl, -strlen(".svg")) === ".svg") {
                $isSvg = true;
                $fileName = "svg-" . random_int(0, PHP_INT_MAX) . ".png";
                $svg = "<?xml version='1.0' encoding='UTF-8' standalone='no'?>" . $this->htmlService->getHTML($imageUrl);

                $this->imagick->readImageBlob($svg, "file.svg");
                $this->imagick->setImageFormat("png24");
                $this->imagick->writeImage($fileName);
                $this->imagick->clear();
                $imageUrl = $fileName;
            }

            $hash = $this->hasher->hash($imageUrl);

            if ($isSvg) {
                unlink($imageUrl);
            }

            return $hash->toInt();
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return "0";
        }
    }

    /**
     * Returns a hash from the image (that is on the current machine)
     * @param string $imageUrl
     * @return string
     */
    public function hashImageLocal(string $imageUrl): string
    {
        return $this->hasher->hash($imageUrl)->toInt();
    }
}