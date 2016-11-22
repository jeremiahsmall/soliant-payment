<?php
namespace Soliant\Payment\AuthentTest\Payment\Hydrator;

use net\authorize\api\contract\v1\CustomerAddressType;
use net\authorize\api\contract\v1\TransactionRequestType;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;
use Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator;
use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;

/**
 * @covers Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService
 */
class TransactionRequestHydratorFactoryTest extends TestCase
{
    public function testAuthorizeAndCaptureServiceHydrateTransactionRequestSubset()
    {
        $subset = 'billTo';
        $data = $this->getData();
        $customerAddressType = $this->getCustomerAddressType();
        $transactionRequestHydrator = new TransactionRequestHydrator($this->getServiceConfig());
        $transactionRequestHydrator->setTransactionRequestType(AuthorizeAndCaptureService::PAYMENT_TRANSACTION_TYPE);
        $customerAddressType = $transactionRequestHydrator->hydrate($data[$subset], $customerAddressType, $subset);
        $this->assertInstanceOf(CustomerAddressType::class, $customerAddressType);
    }

    public function testAuthorizeAndCaptureServiceHydrateTransactionRequest()
    {
        $data = $this->getData();
        $transactionRequestType = $this->getTransactionRequestType(
            AuthorizeAndCaptureService::PAYMENT_TRANSACTION_TYPE
        );
        $transactionRequestHydrator = new TransactionRequestHydrator($this->getServiceConfig());
        $transactionRequestHydrator->setTransactionRequestType(AuthorizeAndCaptureService::PAYMENT_TRANSACTION_TYPE);
        $transactionRequestType = $transactionRequestHydrator->hydrate($data, $transactionRequestType);
        $this->assertInstanceOf(TransactionRequestType::class, $transactionRequestType);
    }

    /**
     * @param string $paymentTransactionType
     * @return TransactionRequestType
     */
    protected function getTransactionRequestType($paymentTransactionType)
    {
        $transactionRequestType = $this->prophesize(TransactionRequestType::class);
        $transactionRequestType->setTransactionType($paymentTransactionType);
        $transactionRequestType->getTransactionType()->willReturn($paymentTransactionType);
        $transactionRequestType->setAmount(Argument::any())->willReturn(null);
        $transactionRequestType->setEmployeeId(Argument::any())->willReturn(null);
        $transactionRequestType->setTaxExempt(Argument::any())->willReturn(null);
        $transactionRequestType->setPoNumber(Argument::any())->willReturn(null);
        return $transactionRequestType->reveal();
    }

    /**
     * @return CustomerAddressType
     */
    public function getCustomerAddressType()
    {
        $customerAddressType = $this->prophesize(CustomerAddressType::class);
        return $customerAddressType->reveal();
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
     * @return array
     */
    protected function getServiceConfig()
    {
        return [
            'authCaptureTransaction' => [
                'field_map' => [
                    'amount' => 'amount',
                    'trackData' => [
                        'track1' => 'track1',
                        'track2' => 'track2',
                    ],
                    'creditCard' => [
                        'cardNumber' => 'cardNumber',
                        'expirationDate' => 'expirationDate',
                        'cardCode' => 'cardCode',
                    ],
                    'bankAccount' => [
                        'accountNumber' => 'accountNumber',
                        'routingNumber' => 'routingNumber',
                        'nameOnAccount' => 'nameOnAccount',
                        'accountType' => 'accountType',
                        'echeckType' => 'echeckType',
                        'checkNumber' => 'checkNumber',
                        'bankName' => 'bankName',
                    ],
                    'profile' => [
                        'createProfile' => 'createProfile', // boolean
                    ],
                    'solution' => [
                        'id' => 'id',
                    ],
                    'order' => [
                        'invoiceNumber' => 'invoiceNumber',
                        'description' => 'description',
                    ],
                    'lineItems' => [
                        'itemId' => 'itemId',
                        'name' => 'name',
                        'description' => 'description',
                        'quantity' => 'quantity',
                        'unitPrice' => 'unitPrice',
                        'taxable' => 'taxable',
                    ],
                    'tax' => [
                        'amount' => 'amount',
                        'name' => 'name',
                        'description' => 'description',
                    ],
                    'duty' => [
                        'amount' => 'amount',
                        'name' => 'name',
                        'description' => 'description',
                    ],
                    'shipping' => [
                        'amount' => 'amount',
                        'name' => 'name',
                        'description' => 'description',
                    ],
                    'taxExempt' => 'taxExempt',
                    'poNumber' => 'poNumber',
                    'customer' => [
                        'type' => 'type', // (individual, business)
                        'id' => 'id',
                        'email' => 'email',
                    ],
                    'billTo' => [
                        'firstName' => 'firstName',
                        'lastName' => 'lastName',
                        'company' => 'company',
                        'address' => 'address',
                        'city' => 'city',
                        'state' => 'state',
                        'zip' => 'zip',
                        'country' => 'country',
                        'phoneNumber' => 'phoneNumber',
                        'faxNumber' => 'faxNumber',
                        'email' => 'email'
                    ],
                    'shipTo' => [
                        'firstName' => 'firstName',
                        'lastName' => 'lastName',
                        'company' => 'company',
                        'address' => 'address',
                        'city' => 'city',
                        'state' => 'state',
                        'zip' => 'zip',
                        'country' => 'country',
                    ],
                    'employeeId' => 'employeeId',
                    'customerIP' => 'customerIP',
                    'cardholderAuthentication' => [
                        'authenticationIndicator' => 'authenticationIndicator',
                        'cardholderAuthenticationValue' => 'cardholderAuthenticationValue',
                    ],
                    'retail' => [
                        'marketType' => 'marketType',
                        'deviceType' => 'deviceType',
                    ],
                    'transactionSettings' => [
                        'settingName' => 'settingName',
                        'settingValue' => 'settingValue',
                    ],
                    'userFields' => [
                        'name' => 'name',
                        'value' => 'value',
                    ],
                ],
            ],
        ];
    }
}
