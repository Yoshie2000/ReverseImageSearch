<?php

namespace Application\Controller;

use Application\Repository\ImageRepositoryInterface;
use Application\Repository\URLRepositoryInterface;
use Application\Service\RabbitMQServiceInterface;
use InvalidArgumentException;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    /** @var URLRepositoryInterface */
    private $urlRepository;
    /** @var ImageRepositoryInterface */
    private $imageRepository;
    /** @var RabbitMQServiceInterface */
    private $rabbitmqService;

    /**
     * IndexController constructor.
     * @param URLRepositoryInterface $urlRepository
     * @param ImageRepositoryInterface $imageRepository
     * @param RabbitMQServiceInterface $rabbitmqService
     */
    public function __construct(
        URLRepositoryInterface $urlRepository,
        ImageRepositoryInterface $imageRepository,
        RabbitMQServiceInterface $rabbitmqService
    ) {
        $this->urlRepository = $urlRepository;
        $this->imageRepository = $imageRepository;
        $this->rabbitmqService = $rabbitmqService;
    }

    /**
     * The index page of the website
     * @return ViewModel
     */
    public function indexAction(): ViewModel
    {
        return new ViewModel([
            "urls" => $this->urlRepository->getAllURLs(),
            "images" => $this->imageRepository->getAllImages()
        ]);
    }

    /**
     * The "Thank you!" page of the website after you submit a URL
     * @return ViewModel
     */
    public function urlAction(): ViewModel
    {
        /** @var Request $request */
        $request = $this->request;
        $url = $request->getPost("url");

        if ($url == null) {
            throw new InvalidArgumentException("No URL supplied");
        }

        $messageCode = $this->urlRepository->saveURL($url);

        if ($messageCode !== URLRepositoryInterface::URL_NOT_CHANGED) {
            $this->rabbitmqService->sendURL($url, "url");
            $this->rabbitmqService->sendURL($url, "imgUrl");
        }

        return new ViewModel([
            "messageCode" => $messageCode,
            "url"         => $url
        ]);
    }
}
