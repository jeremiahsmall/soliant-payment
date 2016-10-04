<?php
namespace Soliant\Payment\AuthentTest\Payment\Request;

use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\util\HttpClient;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;
use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Soliant\Payment\Authnet\Payment\Request\SubsetsService;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Hydrator\ClassMethods;

/**
 * @covers Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService
 */
class TransactionModeFactoryTest extends TestCase
{
    /*
    public function testExceptionIsThrownWithSendRequestInvalidData()
    {
        $fieldMapConfig = $this->getFieldMapConfig();
        $transactionMode = $this->getTransactionMode();
        $merchantAuthentication = $this->getMerchantAuthentication();
        $authorizeAndCaptureService = new AuthorizeAndCaptureService(
            $merchantAuthentication,
            $transactionMode,
            $this->getFieldMapConfig(),
            $this->getCustomerAddressTypeHydrator()
        );
        $this->assertAttributeSame($transactionMode, 'transactionMode', $authorizeAndCaptureService);
        $this->assertAttributeSame($fieldMapConfig, 'fieldMap', $authorizeAndCaptureService);
        $this->assertAttributeSame($merchantAuthentication, 'merchantAuthentication', $authorizeAndCaptureService);
        $this->expectException(\DomainException::class);
        $authorizeAndCaptureService->sendRequest([]);
    }

    public function testExceptionIsThrownWithSendRequestInvalidPaymentType()
    {
        $fieldMapConfig = $this->getFieldMapConfig();
        $transactionMode = $this->getTransactionMode();
        $merchantAuthentication = $this->getMerchantAuthentication();
        $authorizeAndCaptureService = new AuthorizeAndCaptureService(
            $merchantAuthentication,
            $transactionMode,
            $this->getFieldMapConfig(),
            $this->getCustomerAddressTypeHydrator()
        );
        $this->assertAttributeSame($transactionMode, 'transactionMode', $authorizeAndCaptureService);
        $this->assertAttributeSame($fieldMapConfig, 'fieldMap', $authorizeAndCaptureService);
        $this->assertAttributeSame($merchantAuthentication, 'merchantAuthentication', $authorizeAndCaptureService);
        $this->expectException(\DomainException::class);
        $authorizeAndCaptureService->sendRequest([
            'paymentType' => 'Wrong Type',
            'amount' => '5.00',
            'expirationDate' => '2017-01',
            'cardNumber' => '4111111111111111'
        ]);
    }
    */

    public function testSendRequestReturnsAuthCaptureResponse()
    {
        $classMethodsHydrator = $this->getClassMethodsHydrator();
        $fieldMapConfig = $this->getFieldMapConfig();
        $transactionMode = $this->getTransactionMode();
        $merchantAuthentication = $this->getMerchantAuthentication();
        $createTransactionRequest = $this->getCreateTransactionRequest($merchantAuthentication);
        $transactionRequestType = $this->getTransactionRequestType();
        $subsetService = new SubsetsService();
        $authorizeAndCaptureService = new AuthorizeAndCaptureService(
            $createTransactionRequest,
            $transactionMode,
            $fieldMapConfig,
            $classMethodsHydrator,
            $transactionRequestType,
            $subsetService
        );
        $this->assertAttributeSame($transactionMode, 'transactionMode', $authorizeAndCaptureService);
        $this->assertAttributeSame($fieldMapConfig, 'fieldMap', $authorizeAndCaptureService);
        $this->assertAttributeSame($createTransactionRequest, 'createTransactionRequest', $authorizeAndCaptureService);
        $this->assertAttributeSame($classMethodsHydrator, 'hydrator', $authorizeAndCaptureService);
        $this->assertAttributeSame($transactionRequestType, 'transactionRequestType', $authorizeAndCaptureService);
        $this->assertAttributeSame($subsetService, 'subsetsService', $authorizeAndCaptureService);
        $authCaptureResponse = $authorizeAndCaptureService->sendRequest(
            [
                'paymentType' => 'creditCard',
                'amount' => '5.00',
                'expirationDate' => '2017-01',
                'cardNumber' => '4111111111111111'
            ]
        );
        $this->assertInstanceOf(AuthCaptureResponse::class, $authCaptureResponse);

        /*
         * Can't properly test the HttpClient as prophecy doesn't allow reflection of public
         * methods that start with underscore.  Leaving this to be added back if it gets updated.
         *
         * $authCaptureResponse = $authorizeAndCaptureService->sendRequest(
         *   [
         *       'paymentType' => 'creditCard',
         *       'amount' => '5.00',
         *       'expirationDate' => '2017-01',
         *       'cardNumber' => '4111111111111111'
         *   ],
         *   $this->getHttpClient()
         * );
         *
         * ....
         *
         * $resultCode = $authCaptureResponse->createTransactionResponse->getMessages()->getResultCode();
         *
         * $this->assertNotEquals('Error', $resultCode);
         */
    }

    /*
    public function testGetResponse()
    {
        $fieldMapConfig = $this->getFieldMapConfig();
        $transactionMode = $this->getTransactionMode();
        $merchantAuthentication = $this->getMerchantAuthentication();
        $authorizeAndCaptureService = new AuthorizeAndCaptureService(
            $merchantAuthentication,
            $transactionMode,
            $this->getFieldMapConfig(),
            $this->getCustomerAddressTypeHydrator()
        );
        $this->assertAttributeSame($transactionMode, 'transactionMode', $authorizeAndCaptureService);
        $this->assertAttributeSame($fieldMapConfig, 'fieldMap', $authorizeAndCaptureService);
        $this->assertAttributeSame($merchantAuthentication, 'merchantAuthentication', $authorizeAndCaptureService);
        $authorizeAndCaptureService->sendRequest(
            [
                'paymentType' => 'creditCard',
                'amount' => '5.00',
                'expirationDate' => '2017-01',
                'cardNumber' => '4111111111111111'
            ]
        );
        $response = $authorizeAndCaptureService->getResponse();
        $this->assertInstanceOf(AuthCaptureResponse::class, $response);
    }
    */

    /**
     * @param string $mode
     * @return TransactionMode
     */
    protected function getTransactionMode($mode = ANetEnvironment::SANDBOX)
    {
        $transactionMode = $this->prophesize(TransactionMode::class);
        $transactionMode->getTransactionMode()->willReturn($mode);

        return $transactionMode->reveal();
    }

    /**
     * @return ClassMethods
     */
    protected function getClassMethodsHydrator()
    {
        $classMethodsHydrator = $this->prophesize(ClassMethods::class);

        return $classMethodsHydrator->reveal();
    }

    /**
     * @return MerchantAuthenticationType
     */
    protected function getMerchantAuthentication()
    {
        $merchantAuthentication = $this->prophesize(MerchantAuthenticationType::class);

        return $merchantAuthentication->reveal();
    }

    /**
     * @return TransactionRequestType
     */
    protected function getTransactionRequestType()
    {
        $transactionRequestType = $this->prophesize(TransactionRequestType::class);

        return $transactionRequestType->reveal();
    }

    /**
     * @param MerchantAuthenticationType $merchantAuthenticationType
     * @return CreateTransactionRequest
     */
    protected function getCreateTransactionRequest(MerchantAuthenticationType $merchantAuthenticationType)
    {
        $createTransactionRequest = $this->prophesize(CreateTransactionRequest::class);
        $createTransactionRequest->setMerchantAuthentication($merchantAuthenticationType);
        return $createTransactionRequest->reveal();
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        $httpClient = $this->prophesize(HttpClient::class);
        $httpClient->setPostUrl(Argument::any())->willReturn(null);
        $httpClient->sendRequest(Argument::any())->willReturn($this->getXmlResponse());
        return $httpClient->reveal();
    }

    /**
     * @return array
     */
    protected function getFieldMapConfig()
    {
        return [
            'paymentType' => 'paymentType',
            'cardNumber' => 'cardNumber',
            'expirationDate' => 'expirationDate',
            'amount' => 'amount',
        ];
    }

    /**
     * @return string
     */
    protected function getXmlResponse()
    {
        return "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                    <createTransactionResponse xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
                        xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" 
                        xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">
                        <messages>
                            <resultCode>Ok</resultCode>
                            <message>
                                <code>I00001</code>
                                <text>Successful.</text>
                            </message>
                        </messages>
                        <transactionResponse>
                            <responseCode>1</responseCode>
                            <authCode>TB28RK</authCode>
                            <avsResultCode>Y</avsResultCode>
                            <cvvResultCode>P</cvvResultCode>
                            <cavvResultCode>2</cavvResultCode>
                            <transId>20001916118</transId>
                            <refTransID />
                            <transHash>8B8F85782ACB4C99487F9499D9D03050</transHash>
                            <testRequest>0</testRequest>
                            <accountNumber>XXXX1111</accountNumber>
                            <accountType>Visa</accountType>
                            <messages>
                                <message>
                                    <code>1</code>
                                    <description>This transaction has been approved.</description>
                                </message>
                            </messages>
                        </transactionResponse>
                    </createTransactionResponse>";
    }
}
