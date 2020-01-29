<?php

namespace Application\Controller;

use Application\Form\SearchForm;
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
            "urls"   => $this->urlRepository->getAllURLs(),
            "images" => $this->imageRepository->getAllImages()
        ]);
    }

    /**
     * The search page of the website
     * @return ViewModel
     */
    public function searchAction(): ViewModel
    {
        $form = new SearchForm("search-form");
        $form->setAttribute("action", "/results/");
        return new ViewModel([
            "form" => $form
        ]);
    }

    /**
     * The search result page of the website
     * @return ViewModel
     */
    public function resultsAction(): ViewModel
    {
        $form = new SearchForm("search-form");

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();

                $location = "/var/www/html/searchedImages/";

                $allowedExtensions = ["jpg", "jpeg", "png", "svg"];

                $extension = explode('.', $data['image-file']['name']);
                $extension = end($extension);
                $fileName = time() . '.' . $extension;

                $path = $location . $fileName;

                // Check if everything is OK!
                if (0 === $data['image-file']['error'] && in_array($extension, $allowedExtensions)) {
                    move_uploaded_file($data['image-file']['tmp_name'], $path);

                    // Search for the image
                    $similarImages = $this->imageRepository->getImagesInDistance($path, 25);

                    unlink($path);

                    return new ViewModel([
                        "similarImages" => $similarImages
                    ]);
                }

            }
        }

        $this->redirect()->toRoute("search");
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
            $this->rabbitmqService->sendURL("0" . $url, "urlHighPriority");
            $this->rabbitmqService->sendURL("0" . $url, "imgUrlHighPriority");
        }

        return new ViewModel([
            "messageCode" => $messageCode,
            "url"         => $url
        ]);
    }
}
