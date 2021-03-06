<?php


namespace Application\Model;


class ImageModel implements ImageModelInterface
{

    public function __toString()
    {
        return $this->url;
    }

    /** @var int */
    private $id;
    /** @var int */
    private $urlID;
    /** @var string */
    private $url;
    /** @var string */
    private $hash;

    /** ${@inheritDoc} */
    public function getID(): int
    {
        return $this->id;
    }

    /** ${@inheritDoc} */
    public function getURLID(): int
    {
        return $this->urlID;
    }

    /**
     * @return string
     * Returns the URL of the image
     */
    public function getURL(): string
    {
        return $this->url;
    }

    /** ${@inheritDoc} */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $urlID
     */
    public function setUrlID(int $urlID): void
    {
        $this->urlID = $urlID;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }
}