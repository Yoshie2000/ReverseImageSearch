<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Router\Http\RouteMatch;
use Zend\View\Model\ViewModel;

class TestController extends AbstractActionController
{
    /** @var RouteMatch */
    private $routeMatch;

    /**
     * TestController constructor.
     * @param RouteMatch $routeMatch
     */
    public function __construct(RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function testAction()
    {
        /** @var Request $request */
        $request = $this->request;
        $id = $request->getQuery("id", "default");
        $param = $this->routeMatch->getParam("param");
        return new ViewModel(["param" => $param, "id" => $id]);
    }
}
