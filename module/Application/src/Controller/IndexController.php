<?php
namespace Application\Controller;

use Soliant\AuthnetPayment\Authnet\Request\AuthorizeAndCaptureService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @var AuthorizeAndCaptureService
     */
    protected $authorizeAndCaptureService;

    public function __construct(AuthorizeAndCaptureService $authorizeAndCaptureService)
    {
        $this->authorizeAndCaptureService = $authorizeAndCaptureService;
    }

    public function indexAction()
    {
        return new ViewModel($this->authorizeAndCaptureService->getResponse());
    }
}
