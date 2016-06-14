<?php
namespace Soliant\Payment\Demo\Controller;

use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthorizeAndCaptureController extends AbstractActionController
{
    /**
     * @var AuthorizeAndCaptureService
     */
    private $authorizeAndCaptureService;

    /**
     * @param AuthorizeAndCaptureService $authorizeAndCaptureService
     */
    public function __construct(AuthorizeAndCaptureService $authorizeAndCaptureService)
    {
        $this->authorizeAndCaptureService = $authorizeAndCaptureService;
    }

    public function indexAction()
    {
        $response = null;
        $data = null;

        if ($this->getRequest()->isPost()) {
            $response = $this->authorizeAndCaptureService->sendRequest([
                'paymentType' => 'creditCard',
                'amount' => '5.00',
                'expirationDate' => '2017-01',
                'cardNumber' => '4111111111111111'
            ]);

            if ($response->isSuccess()) {
                // do some post processing here
                $data = $response->getData();
            }
        }

        return new ViewModel([
            'messages' => $response === null ? $response : $response->getMessages(),
            'data' => $data
        ]);
    }
}
