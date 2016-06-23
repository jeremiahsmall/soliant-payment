<?php

namespace Soliant\Payment\AuthentTest\Payment\Request\Factory;

use net\authorize\api\contract\v1\MerchantAuthenticationType;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Request\Factory\AuthorizeAndCaptureServiceFactory;
use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Soliant\Payment\Authnet\Payment\Authentication\Factory\AuthorizeAndCaptureServiceFactory
 */
class AuthorizeAndCaptureServiceFactoryTest extends TestCase
{
    public function testExceptionIsThrownWithoutConfig()
    {
        $factory = new AuthorizeAndCaptureServiceFactory();
        $this->expectException(\OutOfBoundsException::class);
        $factory->createService($this->getContainer([]));
    }

    public function testFactoryReturnsConfiguredInstance()
    {
        $config = $this->getConfig();
        $merchantAuthenticationType = $this->prophesize(MerchantAuthenticationType::class)->reveal();
        $transactionMode = $this->prophesize(TransactionMode::class)->reveal();
        $factory = new AuthorizeAndCaptureServiceFactory();
        $instance = $factory->createService($this->getContainer(
            $config,
            $merchantAuthenticationType,
            $transactionMode
        ));
        $this->assertInstanceOf(AuthorizeAndCaptureService::class, $instance);
        $this->assertAttributeSame($merchantAuthenticationType, 'merchantAuthentication', $instance);
        $this->assertAttributeSame($transactionMode, 'transactionMode', $instance);
        $this->assertAttributeSame(
            $config['soliant_payment_authnet']['service']['authorizationAndCapture']['field_map'],
            'fieldMap',
            $instance
        );
    }

    /**
     * @param array $config
     * @param MerchantAuthenticationType $merchantAuthenticationType
     * @param TransactionMode $transactionMode
     * @return ServiceLocatorInterface
     */
    protected function getContainer(
        array $config,
        MerchantAuthenticationType $merchantAuthenticationType = null,
        TransactionMode $transactionMode = null
    ) {
        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(MerchantAuthenticationType::class)->willReturn($merchantAuthenticationType);
        $container->get(TransactionMode::class)->willReturn($transactionMode);

        return $container->reveal();
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return [
            'soliant_payment_authnet' => [
                'service' => [
                    'authorizationAndCapture' => [
                        'field_map' => [
                            'paymentType' => 'paymentType',
                            'cardNumber' => 'cardNumber',
                            'expirationDate' => 'expirationDate',
                            'amount' => 'amount',
                        ],
                    ],
                ],
            ],
        ];
    }
}
