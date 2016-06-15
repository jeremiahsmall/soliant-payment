<?php
namespace Soliant\Payment\Authnet\Payment\Response;

use net\authorize\api\contract\v1\CreateTransactionResponse;
use net\authorize\api\contract\v1\MessagesType;
use net\authorize\api\contract\v1\TransactionResponseType\ErrorsAType\ErrorAType;
use Soliant\Payment\Base\Payment\Response\AbstractResponse;

class AuthCaptureResponse extends AbstractResponse
{
    /**
     * @var CreateTransactionResponse
     */
    protected $createTransactionResponse;

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

    public function isSuccess()
    {
        if (null === $this->createTransactionResponse) {
            return false;
        }

        $transactionResponse = $this->createTransactionResponse->getTransactionResponse();
        $resultCode = $this->createTransactionResponse->getMessages()->getResultCode();

        if ($resultCode === 'Error' && null === $transactionResponse) {
            $this->messages = $this->createTransactionErrorToArray($this->createTransactionResponse->getMessages());
            return false;
        }

        if ($transactionResponse->getResponseCode() !== "1") {
            $this->messages = array_merge(
                $this->createTransactionErrorToArray($this->createTransactionResponse->getMessages()),
                $this->transactionResponseErrorsToArray($transactionResponse->getErrors())
            );
            return false;
        }

        $this->messages = $this->createTransactionErrorToArray($this->createTransactionResponse->getMessages());

        $this->data = [
            'authorizationCode' => $transactionResponse->getAuthCode(),
            'transactionId' => $transactionResponse->getTransId(),
        ];

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
     * @param array $errors
     * @return array
     */
    private function transactionResponseErrorsToArray(array $errors)
    {
        $messages = [];

        /** @var ErrorAType $error */
        foreach ($errors AS $error) {
            $messages[$error->getErrorCode()] = $error->getErrorText();
        }

        return $messages;
    }

    /**
     * @param MessagesType $messageType
     * @return array
     */
    private function createTransactionErrorToArray(MessagesType $messageType)
    {
        $messages = [];

        foreach ($messageType->getMessage() AS $message) {
            $messages[$message->getCode()] = $message->getText();
        }

        return $messages;
    }
}