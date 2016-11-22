<?php
namespace Soliant\Payment\AuthentTest\Payment\Request;

use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\util\HttpClient;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;
use Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator;
use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse;
use Zend\Hydrator\ClassMethods;

/**
 * @covers Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService
 * @covers Soliant\Payment\Authnet\Payment\Request\AbstractRequestService
 */
class TransactionModeFactoryTest extends TestCase
{
    public function testSendRequestReturnsAuthCaptureResponse()
    {
        $transactionMode = $this->getTransactionMode();
        $merchantAuthentication = $this->getMerchantAuthentication();
        $transactionRequestType = $this->getTransactionRequestType();
        $createTransactionRequest = new CreateTransactionRequest();
        $createTransactionRequest->setMerchantAuthentication($merchantAuthentication);
        $transactionRequestHydrator = $this->getTransactionRequestHydrator(
            AuthorizeAndCaptureService::PAYMENT_TRANSACTION_TYPE
        );
        $authorizeAndCaptureService = new AuthorizeAndCaptureService(
            $transactionRequestType,
            $createTransactionRequest,
            $transactionMode,
            $transactionRequestHydrator,
            $this->getSubsetConfig()['subset'],
            $this->getSubsetConfig()['subset_collection'],
            $this->getSubsetConfig()['subset_parent'],
            $this->getSubsetConfig()['subset_alias']
        );
        $this->assertAttributeSame($transactionMode, 'transactionMode', $authorizeAndCaptureService);
        $this->assertAttributeSame($createTransactionRequest, 'createTransactionRequest', $authorizeAndCaptureService);
        $this->assertAttributeSame($transactionRequestType, 'transactionRequestType', $authorizeAndCaptureService);
        $this->assertAttributeSame(
            $transactionRequestHydrator,
            'transactionRequestHydrator',
            $authorizeAndCaptureService
        );
        $authCaptureResponse = $authorizeAndCaptureService->sendRequest($this->getData());
        $this->assertInstanceOf(AuthCaptureResponse::class, $authCaptureResponse);
        $response = $authorizeAndCaptureService->getResponse();
        $this->assertInstanceOf(AuthCaptureResponse::class, $response);

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
     * @param TransactionRequestType $transactionRequestType
     * @return CreateTransactionRequest
     */
    protected function getCreateTransactionRequest(
        MerchantAuthenticationType $merchantAuthenticationType,
        TransactionRequestType $transactionRequestType
    ) {
        $createTransactionRequest = $this->prophesize(CreateTransactionRequest::class);
        $createTransactionRequest->setMerchantAuthentication($merchantAuthenticationType);
        $createTransactionRequest->getMerchantAuthentication()->willReturn($merchantAuthenticationType);
        $createTransactionRequest->setTransactionRequest(Argument::any())->willReturn(null);
        $createTransactionRequest->getTransactionRequest()->willReturn(null);
        $createTransactionRequest->getRefId()->willReturn($transactionRequestType);
        return $createTransactionRequest->reveal();
    }

    /**
     * @param string $transactionRequestType
     * @return object
     */
    protected function getTransactionRequestHydrator($transactionRequestType)
    {
        $transactionRequestHydrator = $this->prophesize(TransactionRequestHydrator::class);
        $transactionRequestHydrator->setTransactionRequestType($transactionRequestType);
        return $transactionRequestHydrator->reveal();
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
    protected function getSubsetConfig()
    {
        return [
            'subset' => [
                'billTo' => \net\authorize\api\contract\v1\CustomerAddressType::class,
                'shipTo' => \net\authorize\api\contract\v1\NameAndAddressType::class,
                'lineItems' => \net\authorize\api\contract\v1\LineItemType::class,
                'tax' => \net\authorize\api\contract\v1\ExtendedAmountType::class,
                'duty' => \net\authorize\api\contract\v1\ExtendedAmountType::class,
                'shipping' => \net\authorize\api\contract\v1\ExtendedAmountType::class,
                'order' => \net\authorize\api\contract\v1\OrderType::class,
                'bankAccount' => \net\authorize\api\contract\v1\BankAccountType::class,
                'creditCard' => \net\authorize\api\contract\v1\CreditCardType::class,
                'trackData' => \net\authorize\api\contract\v1\CreditCardTrackType::class,
                'profile' => \net\authorize\api\contract\v1\CustomerProfilePaymentType::class,
                'customer' => \net\authorize\api\contract\v1\CustomerDataType::class,
                'solution' => \net\authorize\api\contract\v1\SolutionType::class,
                'cardholderAuthentication' => \net\authorize\api\contract\v1\CcAuthenticationType::class,
                'retail' => \net\authorize\api\contract\v1\TransRetailInfoType::class,
                'transactionSettings' => \net\authorize\api\contract\v1\SettingType::class,
                'userFields' => \net\authorize\api\contract\v1\UserFieldType::class,
            ],
            'subset_collection' => [
                'lineItems',
                'userFields',
                'transactionSettings',
            ],
            'subset_parent' => [
                'bankAccount' =>  \net\authorize\api\contract\v1\PaymentType::class,
                'trackData' =>  \net\authorize\api\contract\v1\PaymentType::class,
                'creditCard' =>  \net\authorize\api\contract\v1\PaymentType::class,
            ],
            'subset_alias' => [
                'bankAccount' => 'payment',
                'trackData' => 'payment',
                'creditCard' => 'payment',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            'amount' => '5.00',
            'employeeId' => '12345',
            'order' => [
                'invoiceNumber' => '12345',
                'description' => 'Order Description',
            ],
            'creditCard' => [
                'cardNumber' => '4111111111111111',
                'expirationDate' => '2017-01',
                'cardCode' => '123',
            ],
            'trackData' => [
                'track1' => 'Track 1 Data',
                'track2' => 'Track 2 Data',
            ],
            'lineItems' => [
                [
                    'itemId' => '12345',
                    'name' => 'John Doe',
                    'description' => 'Test Description',
                    'quantity' => '1',
                    'unitPrice' => '5.00',
                    'taxable' => false,
                ],
                [
                    'itemId' => '12346',
                    'name' => 'John Doe 2',
                    'description' => 'Test Description',
                    'quantity' => '10',
                    'unitPrice' => '10.00',
                    'taxable' => true,
                ],
            ],
            'tax' => [
                'amount' => '5.00',
                'name' => 'Tax Name',
                'description' => 'Tax Description',
            ],
            'duty' => [
                'amount' => '5.00',
                'name' => 'Duty Name',
                'description' => 'Duty Description',
            ],
            'shipping' => [
                'amount' => '5.00',
                'name' => 'Shipping Name',
                'description' => 'Shipping Description',
            ],
            'taxExempt' => false,
            'poNumber' => '12345',
            'customer' => [
                'type' => 'individual',
                'id' => '12345',
                'email' => 'test@test.com',
            ],
            'billTo' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'company' => 'Soliant Consulting',
                'address' => '14 N Peoria St.',
                'city' => 'Chicago',
                'state' => 'IL',
                'zip' => '60607',
                'country' => 'US',
                'phoneNumber' => '5555555555',
                'faxNumber' => '5555555555',
                'email' => 'test@test.com',
            ],
            'shipTo' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'company' => 'Soliant Consulting',
                'address' => '14 N Peoria St.',
                'city' => 'Chicago',
                'state' => 'IL',
                'zip' => '60607',
                'country' => 'US',
                'customerIP' => '0.0.0.0'
            ],
            'userFields' => [
                [
                    'name' => 'test field',
                    'value' => 'test value',
                ],
            ],
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
