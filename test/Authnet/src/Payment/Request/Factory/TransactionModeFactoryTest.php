<?php
namespace Soliant\Payment\AuthentTest\Payment\Request\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Request\Factory\TransactionModeFactory;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;

/**
 * @covers \Soliant\Payment\Authnet\Payment\Request\Factory\TransactionModeFactory
 */
class TransactionModeFactoryTest extends TestCase
{
    public function testExceptionIsThrownWithoutConfig()
    {
        $factory = new TransactionModeFactory();
        $this->expectException(\OutOfBoundsException::class);
        $factory->__invoke($this->getContainer([]));
    }

    public function testFactoryReturnsConfiguredInstance()
    {
        $config = $this->getConfig();
        $factory = new TransactionModeFactory();
        $instance = $factory->__invoke($this->getContainer($config));
        $this->assertInstanceOf(TransactionMode::class, $instance);
        $this->assertAttributeSame($config['soliant_payment_authnet']['mode'], 'mode', $instance);
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

    /**
     * @return array
     */
    protected function getConfig()
    {
        return [
            'soliant_payment_authnet' => [
                'mode' => 'sandbox',
            ],
        ];
    }
}
