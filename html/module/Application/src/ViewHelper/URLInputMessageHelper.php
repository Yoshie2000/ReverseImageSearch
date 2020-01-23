<?php

namespace Application\ViewHelper;

use Application\Repository\URLRepositoryInterface;
use Zend\View\Helper\AbstractHelper;

class URLInputMessageHelper extends AbstractHelper
{

    public function __invoke(int $messageCode, string $url)
    {
        switch ($messageCode) {
            case URLRepositoryInterface::URL_NOT_CHANGED:
                return "has already been crawled and hasn't changed since then. It will be ignored.";
            case URLRepositoryInterface::URL_CHANGED:
                return "has already been crawled, but it has changed since then. It will be crawled again as soon as possible.";
            case URLRepositoryInterface::URL_CREATED:
                return "hasn't been crawled yet. It will be crawled as soon as possible.";
        }
    }

}