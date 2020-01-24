<?php


namespace Application\Model;


class ImageModel implements ImageModelInterface
{

    /** @var int */
    private $id;
    /** @var int */
    private $urlID;
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
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }
}