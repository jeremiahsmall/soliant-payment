<?php
namespace Soliant\Payment\AuthentTest\Payment\Hydrator\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Hydrator\Factory\TransactionRequestHydratorFactory;
use Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator;

/**
 * @covers \Soliant\Payment\Authnet\Payment\Hydrator\Factory\TransactionRequestHydratorFactory
 */
class TransactionRequestHydratorFactoryTest extends TestCase
{
    public function testExceptionIsThrownWithoutConfig()
    {
        $factory = new TransactionRequestHydratorFactory();
        $this->expectException(\OutOfBoundsException::class);
        $factory->__invoke($this->getContainer([]));
    }

    public function testFactoryReturnsConfiguredInstance()
    {
        $factory = new TransactionRequestHydratorFactory();
        $config = $this->getConfig();
        $instance = $factory->__invoke($this->getContainer($config));
        $this->assertInstanceOf(TransactionRequestHydrator::class, $instance);
        $this->assertAttributeSame($config['soliant_payment_authnet']['service'], 'serviceConfig', $instance);
    }

    /**
     * @param array $config
     * @return ContainerInterface
     */
    protected function getContainer(array $config)
    {
        $serviceLocator = $this->prophesize(ContainerInterface::class);
        $serviceLocator->get('config')->willReturn($config);
        $serviceLocator->reveal();

        return $serviceLocator->reveal();
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return [
            'soliant_payment_authnet' => [
                'service' => [],
            ],
        ];
    }
}
