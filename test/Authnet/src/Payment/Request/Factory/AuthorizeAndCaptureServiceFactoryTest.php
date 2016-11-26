<?php
namespace Soliant\Payment\AuthentTest\Payment\Request\Factory;

use Interop\Container\ContainerInterface;
use net\authorize\api\contract\v1\CreateTransactionRequest;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator;
use Soliant\Payment\Authnet\Payment\Request\Factory\AuthorizeAndCaptureServiceFactory;
use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;

/**
 * @covers \Soliant\Payment\Authnet\Payment\Request\Factory\AuthorizeAndCaptureServiceFactory
 */
class AuthorizeAndCaptureServiceFactoryTest extends TestCase
{

    public function testExceptionIsThrownWithoutConfig()
    {
        $factory = new AuthorizeAndCaptureServiceFactory();
        $this->expectException(\OutOfBoundsException::class);
        $factory->__invoke($this->getContainer([]));
    }

    public function testFactoryReturnsConfiguredInstance()
    {
        $config = $this->getConfig();
        $transactionMode = $this->prophesize(TransactionMode::class)->reveal();
        $factory = new AuthorizeAndCaptureServiceFactory();
        $instance = $factory->__invoke($this->getContainer(
            $config,
            $transactionMode,
            $this->prophesize(CreateTransactionRequest::class)->reveal(),
            $this->getTransactionRequestHydrator()
        ));
        $this->assertInstanceOf(AuthorizeAndCaptureService::class, $instance);
        $this->assertAttributeSame($transactionMode, 'transactionMode', $instance);
    }

    /**
     * @param array $config
     * @param TransactionMode $transactionMode
     * @param CreateTransactionRequest $createTransactionRequest
     * @param TransactionRequestHydrator $transactionRequestHydrator
     * @return ContainerInterface
     */
    protected function getContainer(
        array $config,
        TransactionMode $transactionMode = null,
        CreateTransactionRequest $createTransactionRequest = null,
        TransactionRequestHydrator $transactionRequestHydrator = null
    ) {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(CreateTransactionRequest::class)->willReturn($createTransactionRequest);
        $container->get(TransactionMode::class)->willReturn($transactionMode);
        $container->get(TransactionRequestHydrator::class)->willReturn($transactionRequestHydrator);

        return $container->reveal();
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
