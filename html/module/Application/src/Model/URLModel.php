<?php


namespace Application\Model;


class URLModel implements URLModelInterface
{

    /** @var int */
    protected $id;

    /** @var string */
    protected $url;

    /** @var string */
    protected $contentHash;

    /** @var int */
    protected $imageCount;

    /** ${@inheritDoc} */
    public function getId()
    {
        return $this->id;
    }

    /** ${@inheritDoc} */
    public function getUrl()
    {
        return $this->url;
    }

    /** ${@inheritDoc} */
    public function getContentHash()
    {
        return $this->contentHash;
    }

    /** ${@inheritDoc} */
    public function getImageCount()
    {
        return $this->imageCount;
    }

    /** @param int $id */
    public function setId($id)
    {
        $this->id = $id;
    }

    /** @param string $url */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /** @param string $contentHash */
    public function setContentHash($contentHash)
    {
        $this->contentHash = $contentHash;
    }

    /** @param int $imageCount */
    public function setImageCount($imageCount)
    {
        $this->imageCount = $imageCount;
    }

}