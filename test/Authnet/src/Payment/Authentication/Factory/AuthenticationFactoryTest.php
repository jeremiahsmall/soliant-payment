<?php
namespace Soliant\Payment\AuthentTest\Payment\Authentication\Factory;

use Interop\Container\ContainerInterface;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Authentication\Factory\AuthenticationFactory;

/**
 * @covers \Soliant\Payment\Authnet\Payment\Authentication\Factory\AuthenticationFactory
 */
class AuthenticationFactoryTest extends TestCase
{
    public function testExceptionIsThrownWithoutConfig()
    {
        $factory = new AuthenticationFactory();
        $this->expectException(\OutOfBoundsException::class);
        $factory->__invoke($this->getContainer([]));
    }

    public function testFactoryReturnsConfiguredInstance()
    {
        $factory = new AuthenticationFactory();
        $instance = $factory->__invoke($this->getContainer(
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
     * @return ContainerInterface
     */
    protected function getContainer(array $config)
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        return $container->reveal();
    }
}
