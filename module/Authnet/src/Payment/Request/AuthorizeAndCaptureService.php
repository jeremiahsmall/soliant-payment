<?php
namespace Soliant\Payment\Authnet\Payment\Request;

use Exception;
use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\CreditCardType;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\CreateTransactionController;
use Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse;
use Soliant\Payment\Base\Payment\AbstractRequestService;

class AuthorizeAndCaptureService extends AbstractRequestService
{
    const FIELD_AMOUNT = 'amount';
    const FIELD_EXPIRATION_DATE = 'expirationDate';
    const FIELD_CARD_NUMBER = 'cardNumber';
    const FIELD_PAYMENT_TYPE = 'paymentType';
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
     * @var array
     */
    protected $fieldMap;

    /**
     * @param MerchantAuthenticationType $merchantAuthentication
     * @param TransactionMode $transactionMode
     * @param array $fieldMap
     */
    public function __construct(
        MerchantAuthenticationType $merchantAuthentication,
        TransactionMode $transactionMode,
        array $fieldMap
    ) {
        $this->merchantAuthentication = $merchantAuthentication;
        $this->transactionMode = $transactionMode;
        $this->fieldMap = $fieldMap;
    }

    /**
     * @param array $data
     * @return AuthCaptureResponse
     * @throws Exception
     */
    public function sendRequest(array $data)
    {
        if (!$this->isValid($data)) {
            throw new Exception(sprintf(
                'Invalid data configuration. sendRequest method must include the following keys: %s, %s, %s, %s',
                self::FIELD_PAYMENT_TYPE,
                self::FIELD_EXPIRATION_DATE,
                self::FIELD_AMOUNT,
                self::FIELD_CARD_NUMBER
            ));
        }

        switch ($data[$this->fieldMap[self::FIELD_PAYMENT_TYPE]]) {
            case self::PAYMENT_TYPE_CREDIT_CARD:
                $creditCard = new CreditCardType();
                $creditCard->setCardNumber($data[$this->fieldMap[self::FIELD_CARD_NUMBER]]);
                $creditCard->setExpirationDate($data[$this->fieldMap[self::FIELD_EXPIRATION_DATE]]);
                break;
            default:
                throw new Exception(sprintf(
                    'Invalid payment type specified.  Payment type must be one of the following: %s, %s',
                    self::PAYMENT_TYPE_CREDIT_CARD,
                    self::PAYMENT_TYPE_ECHECK
                ));
        }

        $paymentOne = new PaymentType();
        $paymentOne->setCreditCard($creditCard);

        $transactionRequestType = new TransactionRequestType();
        $transactionRequestType->setTransactionType(self::PAYMENT_TRANSACTION_TYPE);
        $transactionRequestType->setAmount($data[$this->fieldMap[self::FIELD_AMOUNT]]);
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

    /**
     * @param array $data
     * @return bool
     */
    private function isValid(array $data)
    {
        $requiredField = [
            self::FIELD_AMOUNT,
            self::FIELD_CARD_NUMBER,
            self::FIELD_EXPIRATION_DATE,
            self::FIELD_PAYMENT_TYPE
        ];

        foreach ($requiredField AS $key) {
            if (!array_key_exists($key, $data)) {
                return false;
            }
        }

        return true;
    }
}
