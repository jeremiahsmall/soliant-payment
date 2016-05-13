<?php
namespace Soliant\AuthnetPayment\Authnet\Request;

use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\CreditCardType;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\CreateTransactionController;
use Soliant\AuthnetPayment\Authnet\Response\AuthCaptureResponse;
use Soliant\PaymentBase\Payment\Request\AbstractRequest;

class AuthCaptureRequest extends AbstractRequest
{
    const PAYMENT_TYPE_CREDIT_CARD = 'creditCard';
    const PAYMENT_TYPE_ECHECK = 'eCheck';
    const PAYMENT_TRANSACTION_TYPE = 'authCaptureTransaction';

    /**
     * @var MerchantAuthenticationType
     */
    protected $merchantAuthentication;

    /**
     * @var TransactionMode
     */
    protected $transactionMode;

    /**
     * @var AuthCaptureResponse
     */
    protected $authCaptureResponse;

    /**
     * @param MerchantAuthenticationType $merchantAuthentication
     * @param TransactionMode $transactionMode
     */
    public function __construct(MerchantAuthenticationType $merchantAuthentication, TransactionMode $transactionMode)
    {
        $this->merchantAuthentication = $merchantAuthentication;
        $this->transactionMode = $transactionMode;
    }

    /**
     * @param array $data
     * @return AuthCaptureResponse
     * @throws Exception
     */
    public function sendRequest(array $data)
    {
        define("AUTHORIZENET_LOG_FILE", "phplog");

        switch ($data['paymentType']) {
            case self::PAYMENT_TYPE_CREDIT_CARD:
                $creditCard = new CreditCardType();
                $creditCard->setCardNumber($data['cardNumber']);
                $creditCard->setExpirationDate($data['expirationDate']);
                break;
            default:
                throw new Exception('Invalid payment type specificed');
        }

        $paymentOne = new PaymentType();
        $paymentOne->setCreditCard($creditCard);

        $transactionRequestType = new TransactionRequestType();
        $transactionRequestType->setTransactionType(self::PAYMENT_TRANSACTION_TYPE);
        $transactionRequestType->setAmount($data['amount']);
        $transactionRequestType->setPayment($paymentOne);

        $request = new CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setTransactionRequest($transactionRequestType);
        
        $controller = new CreateTransactionController($request);
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
