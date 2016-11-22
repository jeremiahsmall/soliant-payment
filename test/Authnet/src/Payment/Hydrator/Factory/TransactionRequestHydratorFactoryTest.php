<?php
namespace Soliant\Payment\AuthentTest\Payment\Hydrator\Factory;

use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Hydrator\Factory\TransactionRequestHydratorFactory;
use Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator;
use Zend\Hydrator\HydratorPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Soliant\Payment\Authnet\Payment\Hydrator\Factory\TransactionRequestHydratorFactory
 */
class TransactionRequestHydratorFactoryTest extends TestCase
{
    public function testExceptionIsThrownWithoutConfig()
    {
        $factory = new TransactionRequestHydratorFactory();
        $this->expectException(\OutOfBoundsException::class);
        $factory->createService($this->getContainer([]));
    }

    public function testFactoryReturnsConfiguredInstance()
    {
        $factory = new TransactionRequestHydratorFactory();
        $config = $this->getConfig();
        $instance = $factory->createService($this->getContainer($config));
        $this->assertInstanceOf(TransactionRequestHydrator::class, $instance);
        $this->assertAttributeSame($config['soliant_payment_authnet']['service'], 'serviceConfig', $instance);
    }

    /**
     * @param array $config
     * @return ServiceLocatorInterface
     */
    protected function getContainer(array $config)
    {
        $hydratorPluginManager = $this->prophesize(HydratorPluginManager::class);
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $serviceLocator->get('config')->willReturn($config);
        $serviceLocator->reveal();
        $hydratorPluginManager->getServiceLocator()->willReturn($serviceLocator);

        return $hydratorPluginManager->reveal();
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
