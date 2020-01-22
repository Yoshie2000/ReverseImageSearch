<?php


namespace Application\Model;


interface URLModelInterface
{

    public function getId();

    public function getUrl();

    public function getContentHash();

    public function getImageCount();

}