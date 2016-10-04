<?php
namespace Soliant\Payment\Authnet\Payment\Response;

use net\authorize\api\contract\v1\CreateTransactionResponse;
use net\authorize\api\contract\v1\MessagesType;
use net\authorize\api\contract\v1\TransactionResponseType;
use net\authorize\api\contract\v1\TransactionResponseType\ErrorsAType\ErrorAType;
use net\authorize\api\contract\v1\UserFieldType;
use Soliant\Payment\Base\Payment\Response\AbstractResponse;

class AuthCaptureResponse extends AbstractResponse
{
    const TRANSACTION_RESPONSE = 'transactionResponse';
    const PROFILE_RESPONSE = 'profileResponse';

    /**
     * @var CreateTransactionResponse
     */
    public $createTransactionResponse;

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param CreateTransactionResponse $createTransactionResponse
     */
    public function __construct(CreateTransactionResponse $createTransactionResponse)
    {
        $this->createTransactionResponse = $createTransactionResponse;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        $transactionResponse = $this->createTransactionResponse->getTransactionResponse();
        $resultCode = $this->createTransactionResponse->getMessages()->getResultCode();

        if ($resultCode === 'Error' && null === $transactionResponse) {
            $this->messages = [
                self::TRANSACTION_RESPONSE =>
                    $this->createTransactionErrorToArray($this->createTransactionResponse->getMessages()),
            ];
            return false;
        }

        if ($transactionResponse->getResponseCode() !== "1") {
            $this->messages = [
                self::TRANSACTION_RESPONSE => array_merge(
                    $this->createTransactionErrorToArray($this->createTransactionResponse->getMessages()),
                    $this->transactionResponseErrorsToArray($transactionResponse->getErrors())
                ),
            ];
            return false;
        }

        $this->messages = [
            self::TRANSACTION_RESPONSE =>
                $this->createTransactionErrorToArray($this->createTransactionResponse->getMessages()),
        ];

        $this->data = [
            self::TRANSACTION_RESPONSE => [
                'responseCode' => $transactionResponse->getResponseCode(),
                'authCode' => $transactionResponse->getAuthCode(),
                'avsResultCode' => $transactionResponse->getAvsResultCode(),
                'ccvResultCode' => $transactionResponse->getCvvResultCode(),
                'cavvResultCode' => $transactionResponse->getCavvResultCode(),
                'transId' => $transactionResponse->getTransId(),
                'refTransID' => $transactionResponse->getRefTransID(),
                'transHash' => $transactionResponse->getTransHash(),
                'accountNumber' => $transactionResponse->getAccountNumber(),
                'accountType' => $transactionResponse->getAccountType(),
                'userFields' => $this->getUserFieldsFromTransactionResponse($transactionResponse),
            ],
        ];

        $profileResponse = $this->createTransactionResponse->getProfileResponse();

        if (null !== $profileResponse) {
            $this->messages = array_merge(
                $this->messages,
                [self::PROFILE_RESPONSE => $this->createTransactionErrorToArray($profileResponse->getMessages()),]
            );

            $this->data = array_merge(
                $this->data,
                [
                    'profileResponse' => [
                        'resultCode' => $profileResponse->getMessages()->getResultCode(),
                        'customerProfileId' => $profileResponse->getCustomerProfileId(),
                        'customerPaymentProfileIdList' => $profileResponse->getCustomerPaymentProfileIdList(),
                        'customerShippingAddressIdList' => $profileResponse->getCustomerShippingAddressIdList(),
                    ],
                ]
            );
        }

        return true;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array|null $errors
     * @return array
     */
    private function transactionResponseErrorsToArray(array $errors = null)
    {
        $messages = [];

        if (null !== $errors) {
            /** @var ErrorAType $error */
            foreach ($errors as $error) {
                $messages[$error->getErrorCode()] = $error->getErrorText();
            }
        }

        return $messages;
    }

    /**
     * @param MessagesType|null $messageType
     * @return array
     */
    private function createTransactionErrorToArray(MessagesType $messageType = null)
    {
        $messages = [];

        if (null !== $messageType) {
            foreach ($messageType->getMessage() as $message) {
                $messages[$message->getCode()] = $message->getText();
            }
        }

        return $messages;
    }

    /**
     * @param TransactionResponseType $transactionResponseType
     * @return array
     */
    private function getUserFieldsFromTransactionResponse(TransactionResponseType $transactionResponseType)
    {
        $userFields = null;
        $responseUserFields = $transactionResponseType->getUserFields();
        if (null !== $responseUserFields && is_array($responseUserFields)) {
            /** @var UserFieldType $responseUserField */
            foreach ($responseUserFields as $responseUserField) {
                $userFields[] = ['name' => $responseUserField->getName(), 'value' => $responseUserField->getValue(),];
            }
        }

        return $userFields;
    }
}
