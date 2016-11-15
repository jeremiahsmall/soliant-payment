<?php
namespace Soliant\Payment\AuthentTest\Payment\Request\Factory;

use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Request\Factory\CreateTransactionRequestFactory;
use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Soliant\Payment\Authnet\Payment\Request\Factory\CreateTransactionRequestFactory
 */
class CreateTransactionRequestFactoryTest extends TestCase
{
    public function testFactoryReturnsConfiguredInstance()
    {
        $factory = new CreateTransactionRequestFactory();
        $merchantAuthenticationType = $this->getMerchantAuthenticationType();
        $instance = $factory->createService($this->getContainer($merchantAuthenticationType));
        $this->assertInstanceOf(CreateTransactionRequest::class, $instance);
        $this->assertAttributeSame($merchantAuthenticationType, 'merchantAuthentication', $instance);
    }

    /**
     * @param MerchantAuthenticationType $merchantAuthenticationType
     * @return ServiceLocatorInterface
     */
    protected function getContainer(MerchantAuthenticationType $merchantAuthenticationType)
    {
        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(MerchantAuthenticationType::class)->willReturn($merchantAuthenticationType);

        return $container->reveal();
    }

    /**
     * @return MerchantAuthenticationType
     */
    protected function getMerchantAuthenticationType()
    {
        $merchantAuthenticationType = $this->prophesize(MerchantAuthenticationType::class);
        return $merchantAuthenticationType->reveal();
    }
}
