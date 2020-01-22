<?php

namespace Application\Service;

interface URLServiceInterface
{

    public function getAllURLs();

    public function getURLInfo($url);

    public function saveURL($url);

    public function getHTML($url);

    public function getURLHash($url);

}