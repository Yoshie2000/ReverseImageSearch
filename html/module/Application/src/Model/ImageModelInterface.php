<?php


namespace Application\Model;


interface ImageModelInterface
{

    /**
     * Returns the ID of the image
     * @return int
     */
    public function getID():int;

    /**
     * Returns the ID of the URL that the image comes from
     * @return int
     */
    public function getURLID():int;

    /**
     * Returns the hash of the image
     * @return string
     */
    public function getHash():string;

}