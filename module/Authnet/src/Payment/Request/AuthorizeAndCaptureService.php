<?php
namespace Soliant\Payment\Authnet\Payment\Request;

use net\authorize\api\controller\CreateTransactionController;
use Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse;

class AuthorizeAndCaptureService extends AbstractRequestService
{
    const PAYMENT_TRANSACTION_TYPE = 'authCaptureTransaction';

    /**
     * @var AuthCaptureResponse
     */
    protected $authCaptureResponse;

    /**
     * @param array $data
     * @return AuthCaptureResponse
     */
    public function sendRequest(array $data)
    {
        $this->transactionRequestType->setTransactionType(self::PAYMENT_TRANSACTION_TYPE);
        $this->hydrate($data);
        $controller = new CreateTransactionController($this->createTransactionRequest);
        $response = $controller->executeWithApiResponse($this->transactionMode->getTransactionMode());

        $this->authCaptureResponse = new AuthCaptureResponse($response);
        return $this->authCaptureResponse;
    }

    /**
     * @return AuthCaptureResponse
     */
    public function getResponse()
    {
        return $this->authCaptureResponse;
    }
}
