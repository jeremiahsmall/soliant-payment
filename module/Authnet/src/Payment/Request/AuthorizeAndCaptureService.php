<?php
namespace Soliant\Payment\Authnet\Payment\Request;

use DomainException;
use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\CreditCardType;
use net\authorize\api\contract\v1\CustomerAddressType;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\NameAndAddressType;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\CreateTransactionController;
use Soliant\Payment\Authnet\Payment\Hydrator\CustomerAddressTypeHydrator;
use Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse;
use Soliant\Payment\Base\Payment\AbstractRequestService;

class AuthorizeAndCaptureService extends AbstractRequestService
{
    const FIELD_AMOUNT = 'amount';
    const FIELD_EXPIRATION_DATE = 'expirationDate';
    const FIELD_CARD_NUMBER = 'cardNumber';
    const FIELD_PAYMENT_TYPE = 'paymentType';
    const FIELD_BILL_TO_FIRST_NAME = 'firstName';
    const FIELD_BILL_TO_LAST_NAME = 'lastName';
    const FIELD_BILL_TO_COMPANY = 'company';
    const FIELD_BILL_TO_ADDRESS = 'address';
    const FIELD_BILL_TO_CITY = 'city';
    const FIELD_BILL_TO_STATE = 'state';
    const FIELD_BILL_TO_ZIP = 'zip';
    const FIELD_BILL_TO_COUNTRY = 'country';
    const FIELD_BILL_TO_PHONE_NUMBER = 'phoneNumber';
    const FIELD_BILL_TO_FAX_NUMBER = 'faxNumber';

    const PAYMENT_TYPE_CREDIT_CARD = 'creditCard';
    const PAYMENT_TYPE_ECHECK = 'eCheck';
    const PAYMENT_TRANSACTION_TYPE = 'authCaptureTransaction';

    const BILL_TO_ADDRESS = 'billTo';

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
     * @var CustomerAddressTypeHydrator
     */
    protected $customerAddressTypeHydrator;

    /**
     * @param MerchantAuthenticationType $merchantAuthentication
     * @param TransactionMode $transactionMode
     * @param array $fieldMap
     * @param CustomerAddressTypeHydrator $customerAddressTypeHydrator
     */
    public function __construct(
        MerchantAuthenticationType $merchantAuthentication,
        TransactionMode $transactionMode,
        array $fieldMap,
        CustomerAddressTypeHydrator $customerAddressTypeHydrator
    ) {
        $this->merchantAuthentication = $merchantAuthentication;
        $this->transactionMode = $transactionMode;
        $this->fieldMap = $fieldMap;
        $this->customerAddressTypeHydrator = $customerAddressTypeHydrator;
    }

    /**
     * @param array $data
     * @return AuthCaptureResponse
     * @throws Exception
     */
    public function sendRequest(array $data)
    {
        if (!$this->isValid($data)) {
            throw new DomainException(sprintf(
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
                throw new DomainException(sprintf(
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

        if (array_key_exists(self::BILL_TO_ADDRESS, $data) && is_array($data[self::BILL_TO_ADDRESS])) {
            $billToAddress = new CustomerAddressType();
            $billToAddress = $this->customerAddressTypeHydrator->hydrate(
                $data[self::BILL_TO_ADDRESS],
                $billToAddress,
                $this->fieldMap[self::BILL_TO_ADDRESS]
            );

            $transactionRequestType->setBillTo($billToAddress);
        }

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

        foreach ($requiredField as $key) {
            if (!array_key_exists($key, $data)) {
                return false;
            }
        }

        return true;
    }
}
