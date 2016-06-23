<?php

namespace Soliant\Payment\AuthentTest\Payment\Request\Factory;

use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Request\Factory\TransactionModeFactory;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Soliant\Payment\Authnet\Payment\Authentication\Factory\TransactionModeFactory
 */
class TransactionModeFactoryTest extends TestCase
{
    public function testExceptionIsThrownWithoutConfig()
    {
        $factory = new TransactionModeFactory();
        $this->expectException(\OutOfBoundsException::class);
        $factory->createService($this->getContainer([]));
    }

    public function testFactoryReturnsConfiguredInstance()
    {
        $config = $this->getConfig();
        $factory = new TransactionModeFactory();
        $instance = $factory->createService($this->getContainer($config));
        $this->assertInstanceOf(TransactionMode::class, $instance);
        $this->assertAttributeSame($config['soliant_payment_authnet']['mode'], 'mode', $instance);
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
