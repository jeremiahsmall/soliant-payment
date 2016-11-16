<?php
namespace Soliant\Payment\AuthentTest\Payment\Request\Factory;

use net\authorize\api\contract\v1\CreateTransactionRequest;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator;
use Soliant\Payment\Authnet\Payment\Request\Factory\AuthorizeAndCaptureServiceFactory;
use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Zend\Hydrator\HydratorPluginManager;
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
        $transactionMode = $this->prophesize(TransactionMode::class)->reveal();
        $factory = new AuthorizeAndCaptureServiceFactory();
        $instance = $factory->createService($this->getContainer(
            $config,
            $transactionMode,
            $this->getHydratorManager($this->getTransactionRequestHydrator()),
            $this->prophesize(CreateTransactionRequest::class)->reveal()
        ));
        $this->assertInstanceOf(AuthorizeAndCaptureService::class, $instance);
        $this->assertAttributeSame($transactionMode, 'transactionMode', $instance);
    }

    /**
     * @param array $config
     * @param TransactionMode $transactionMode
     * @param HydratorPluginManager $hydratorPluginManager
     * @param CreateTransactionRequest $createTransactionRequest
     * @return object
     */
    protected function getContainer(
        array $config,
        TransactionMode $transactionMode = null,
        HydratorPluginManager $hydratorPluginManager = null,
        CreateTransactionRequest $createTransactionRequest = null
    ) {
        $hydratorManager = $this->prophesize(HydratorPluginManager::class);
        $hydratorManager->reveal();

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(CreateTransactionRequest::class)->willReturn($createTransactionRequest);
        $container->get(TransactionMode::class)->willReturn($transactionMode);
        $container->get('HydratorManager')->willReturn($hydratorPluginManager);

        return $container->reveal();
    }

    /**
     * @return HydratorPluginManager
     */
    protected function getHydratorManager(TransactionRequestHydrator $transactionRequestHydrator)
    {
        $hydratorManager = $this->prophesize(HydratorPluginManager::class);
        $hydratorManager->get(TransactionRequestHydrator::class)->willReturn($transactionRequestHydrator);
        return $hydratorManager->reveal();
    }

    /**
     * @return TransactionRequestHydrator
     */
    protected function getTransactionRequestHydrator()
    {
        $transactionRequestHydrator = $this->prophesize(TransactionRequestHydrator::class);
        return $transactionRequestHydrator->reveal();
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return [
            'soliant_payment_authnet' => [
                'subset' => [],
                'subset_collection' => [],
                'subset_parent' => [],
                'subset_alias' => [],
            ],
        ];
    }
}
