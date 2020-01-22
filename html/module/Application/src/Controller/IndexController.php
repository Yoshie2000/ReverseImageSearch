<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Service\RabbitMQServiceInterface;
use Application\Service\URLServiceInterface;
use http\Exception\InvalidArgumentException;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    /** @var URLServiceInterface */
    private $urlService;
    /** @var RabbitMQServiceInterface */
    private $rabbitmqService;

    public function __construct(URLServiceInterface $urlService, RabbitMQServiceInterface $rabbitmqService)
    {
        $this->urlService = $urlService;
        $this->rabbitmqService = $rabbitmqService;
    }

    public function indexAction()
    {
        return new ViewModel([
            "urls" => $this->urlService->getAllURLs()
        ]);
    }

    public function urlAction()
    {
        /** @var Request $request */
        $request = $this->request;
        $url = $request->getPost("url");

        if ($url == null) {
            throw new InvalidArgumentException("No URL supplied");
        }

        $this->urlService->saveURL($url);
        $this->rabbitmqService->sendURL($url);

        return new ViewModel([
            "url" => $url
        ]);
    }
}
