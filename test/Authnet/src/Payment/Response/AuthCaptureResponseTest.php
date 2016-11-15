<?php
namespace Soliant\Payment\AuthentTest\Payment\Response;

use net\authorize\api\contract\v1\CreateTransactionResponse;
use net\authorize\api\contract\v1\MessagesType;
use net\authorize\api\contract\v1\MessagesType\MessageAType;
use net\authorize\api\contract\v1\TransactionResponseType;
use net\authorize\api\contract\v1\TransactionResponseType\ErrorsAType\ErrorAType;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse;

/**
 * @covers Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse
 */
class AuthCaptureReponseTest extends TestCase
{
    public function testResponseIsSuccessReturnsTrue()
    {
        $createTransactionResponse = $this->getCreateTransactionResponse('1', 'Ok', true);
        $authCaptureResponse = new AuthCaptureResponse($createTransactionResponse);
        $this->assertAttributeSame($createTransactionResponse, 'createTransactionResponse', $authCaptureResponse);
        $this->assertTrue($authCaptureResponse->isSuccess());
    }

    public function testResponseIsSuccessReturnsFalseForErrorResultCodeAndNullTransactionResponse()
    {
        $createTransactionResponse = $this->getCreateTransactionResponse('1', 'Error', null);
        $authCaptureResponse = new AuthCaptureResponse($createTransactionResponse);
        $this->assertAttributeSame($createTransactionResponse, 'createTransactionResponse', $authCaptureResponse);
        $this->assertFalse($authCaptureResponse->isSuccess());
    }

    public function testResponseIsSuccessReturnsFalseForInvalidResultCode()
    {
        $createTransactionResponse = $this->getCreateTransactionResponse('0', 'Ok', true);
        $authCaptureResponse = new AuthCaptureResponse($createTransactionResponse);
        $this->assertAttributeSame($createTransactionResponse, 'createTransactionResponse', $authCaptureResponse);
        $this->assertFalse($authCaptureResponse->isSuccess());
    }

    public function testResponseIsSuccessReturnsFalseAndGetMessages()
    {
        $createTransactionResponse = $this->getCreateTransactionResponse('0', 'Error', null);
        $authCaptureResponse = new AuthCaptureResponse($createTransactionResponse);
        $this->assertAttributeSame($createTransactionResponse, 'createTransactionResponse', $authCaptureResponse);
        $this->assertFalse($authCaptureResponse->isSuccess());
        $this->assertContains(
            'There was a message',
            $authCaptureResponse->getMessages()[AuthCaptureResponse::TRANSACTION_RESPONSE]
        );
    }

    public function testResponseIsSuccessReturnsTrueAndGetMessages()
    {
        $createTransactionResponse = $this->getCreateTransactionResponse('1', 'Ok', true);
        $authCaptureResponse = new AuthCaptureResponse($createTransactionResponse);
        $this->assertAttributeSame($createTransactionResponse, 'createTransactionResponse', $authCaptureResponse);
        $this->assertTrue($authCaptureResponse->isSuccess());
        $this->assertContains(
            'There was a message',
            $authCaptureResponse->getMessages()[AuthCaptureResponse::TRANSACTION_RESPONSE]
        );
    }

    public function testResponseGetData()
    {
        $createTransactionResponse = $this->getCreateTransactionResponse('1', 'Ok', true);
        $authCaptureResponse = new AuthCaptureResponse($createTransactionResponse);
        $this->assertAttributeSame($createTransactionResponse, 'createTransactionResponse', $authCaptureResponse);
        $this->assertTrue($authCaptureResponse->isSuccess());
        $this->assertContains('transId', $authCaptureResponse->getData()[AuthCaptureResponse::TRANSACTION_RESPONSE]);
    }

    /**
     * @param string $responseCode
     * @param string $resultCode
     * @param null|bool $transactionResponseType
     * @param string $authCode
     * @param string $transactionId
     * @param array $transactionResponseTypeErrors
     * @return CreateTransactionResponse
     */
    protected function getCreateTransactionResponse(
        $responseCode = '1',
        $resultCode = 'Ok',
        $transactionResponseType = null,
        $authCode = 'Authorization Code',
        $transactionId = 'Transaction Id',
        array $transactionResponseTypeErrors = null,
        array $messageTypeMessages = null
    ) {
        if (null !== $transactionResponseType) {
            $errorTypeA = $this->prophesize(ErrorAType::class);
            $errorTypeA->getErrorCode('99999');
            $errorTypeA->getErrorText('There was an error');
            $errorTypeA->reveal();

            $transactionResponseType = $this->prophesize(TransactionResponseType::class);
            $transactionResponseType->getResponseCode()->willReturn($responseCode);
            $transactionResponseType->getErrors()->willReturn(
                null === $transactionResponseTypeErrors ? [$errorTypeA] : $transactionResponseTypeErrors
            );
            $transactionResponseType->getAuthCode()->willReturn($authCode);
            $transactionResponseType->getTransId()->willReturn($transactionId);
            $transactionResponseType->getAvsResultCode()->willReturn(true);
            $transactionResponseType->getCvvResultCode()->willReturn(true);
            $transactionResponseType->getCavvResultCode()->willReturn(true);
            $transactionResponseType->getRefTransID()->willReturn(true);
            $transactionResponseType->getTransHash()->willReturn(true);
            $transactionResponseType->getAccountNumber()->willReturn(true);
            $transactionResponseType->getAccountType()->willReturn(true);
            $transactionResponseType->getUserFields()->willReturn([]);
            $transactionResponseType->reveal();
        }

        $messageTypeA = $this->prophesize(MessageAType::class);
        $messageTypeA->getCode()->willReturn('99999');
        $messageTypeA->getText()->willReturn('There was a message');

        $messageType = $this->prophesize(MessagesType::class);
        $messageType->getResultCode()->willReturn($resultCode);
        $messageType->getMessage()->willReturn(null === $messageTypeMessages ? [$messageTypeA] : $messageTypeMessages);
        $messageType->reveal();

        $createTransactionResponse = $this->prophesize(CreateTransactionResponse::class);
        $createTransactionResponse->getTransactionResponse()->willReturn($transactionResponseType);
        $createTransactionResponse->getProfileResponse()->willReturn(null);
        $createTransactionResponse->getMessages()->willReturn($messageType);

        return $createTransactionResponse->reveal();
    }
}
