<?php
namespace Soliant\Payment\AuthentTest\Payment\Authentication\Factory;

use net\authorize\api\contract\v1\MerchantAuthenticationType;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Authentication\Factory\AuthenticationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Soliant\Payment\Authnet\Payment\Authentication\Factory\AuthenticationFactory
 */
class AuthenticationFactoryTest extends TestCase
{
    public function testExceptionIsThrownWithoutConfig()
    {
        $factory = new AuthenticationFactory();
        $this->expectException(\OutOfBoundsException::class);
        $factory->createService($this->getContainer([]));
    }

    public function testFactoryReturnsConfiguredInstance()
    {
        $factory = new AuthenticationFactory();
        $instance = $factory->createService($this->getContainer(
            [
                'soliant_payment_authnet' => [
                    'login' => null,
                    'key' => null,
                ],
            ]
        ));
        $this->assertInstanceOf(MerchantAuthenticationType::class, $instance);
        $this->assertAttributeSame(null, 'name', $instance);
        $this->assertAttributeSame(null, 'transactionKey', $instance);
    }

    /**
     * @param array $config
     * @return ServiceLocatorInterface
     */
    protected function getContainer(array $config)
    {
        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get('config')->willReturn($config);

        return $container->reveal();
    }
}
