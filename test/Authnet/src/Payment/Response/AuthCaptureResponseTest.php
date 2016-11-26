<?php
namespace Soliant\Payment\AuthentTest\Payment\Response;

use net\authorize\api\contract\v1\CreateProfileResponseType;
use net\authorize\api\contract\v1\CreateTransactionResponse;
use net\authorize\api\contract\v1\MessagesType;
use net\authorize\api\contract\v1\MessagesType\MessageAType;
use net\authorize\api\contract\v1\TransactionResponseType;
use net\authorize\api\contract\v1\TransactionResponseType\ErrorsAType\ErrorAType;
use net\authorize\api\contract\v1\UserFieldType;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse;

/**
 * @covers \Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse
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

    public function testResponseProfileResponse()
    {
        $createTransactionResponse = $this->getCreateTransactionResponse('1', 'Ok', true, true);
        $authCaptureResponse = new AuthCaptureResponse($createTransactionResponse);
        $this->assertAttributeSame($createTransactionResponse, 'createTransactionResponse', $authCaptureResponse);
        $this->assertTrue($authCaptureResponse->isSuccess());
        $this->assertContains('resultCode', $authCaptureResponse->getData()[AuthCaptureResponse::PROFILE_RESPONSE]);
    }

    public function testResponseGetData()
    {
        $createTransactionResponse = $this->getCreateTransactionResponse('1', 'Ok', true);
        $authCaptureResponse = new AuthCaptureResponse($createTransactionResponse);
        $this->assertAttributeSame($createTransactionResponse, 'createTransactionResponse', $authCaptureResponse);
        $this->assertTrue($authCaptureResponse->isSuccess());
        $this->assertContains('transId', $authCaptureResponse->getData()[AuthCaptureResponse::TRANSACTION_RESPONSE]);
        $this->assertContains(
            'name',
            $authCaptureResponse->getData()[AuthCaptureResponse::TRANSACTION_RESPONSE]['userFields'][0]
        );
    }

    /**
     * @param string $responseCode
     * @param string $resultCode
     * @param null|bool $transactionResponseType
     * @param null|bool $profileResponse
     * @param string $authCode
     * @param string $transactionId
     * @param array $transactionResponseTypeErrors
     * @param array $messageTypeMessages
     * @return CreateTransactionResponse
     */
    protected function getCreateTransactionResponse(
        $responseCode = '1',
        $resultCode = 'Ok',
        $transactionResponseType = null,
        $profileResponse = null,
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

            $userFieldType = $this->prophesize(UserFieldType::class);
            $userFieldType->getName()->willReturn(true);
            $userFieldType->getValue()->willReturn(true);
            $userFieldType->reveal();

            $transactionResponseType->getUserFields()->willReturn([$userFieldType]);
            $transactionResponseType->reveal();
        }

        $messageTypeA = $this->prophesize(MessageAType::class);
        $messageTypeA->getCode()->willReturn('99999');
        $messageTypeA->getText()->willReturn('There was a message');

        $messageType = $this->prophesize(MessagesType::class);
        $messageType->getResultCode()->willReturn($resultCode);
        $messageType->getMessage()->willReturn(null === $messageTypeMessages ? [$messageTypeA] : $messageTypeMessages);
        $messageType->reveal();

        $profileResponseType = null;
        if (null !== $profileResponse) {
            $profileResponseType = $this->prophesize(CreateProfileResponseType::class);
            $profileResponseType->getMessages()->willReturn($messageType);
            $profileResponseType->getCustomerProfileId()->willReturn(true);
            $profileResponseType->getCustomerPaymentProfileIdList()->willReturn(true);
            $profileResponseType->getCustomerShippingAddressIdList()->willReturn(true);
            $profileResponseType->reveal();
        }

        $createTransactionResponse = $this->prophesize(CreateTransactionResponse::class);
        $createTransactionResponse->getTransactionResponse()->willReturn($transactionResponseType);
        $createTransactionResponse->getProfileResponse()->willReturn($profileResponseType);
        $createTransactionResponse->getMessages()->willReturn($messageType);

        return $createTransactionResponse->reveal();
    }
}
