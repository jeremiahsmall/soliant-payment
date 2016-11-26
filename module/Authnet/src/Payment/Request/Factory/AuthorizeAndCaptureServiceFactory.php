<?php
namespace Soliant\Payment\Authnet\Payment\Request\Factory;

use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\TransactionRequestType;
use OutOfBoundsException;
use Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator;
use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Interop\Container\ContainerInterface;

class AuthorizeAndCaptureServiceFactory
{
    public function __invoke(ContainerInterface $sm)
    {
        $config = $sm->get('config');

        if (!array_key_exists('soliant_payment_authnet', $config)
            || !array_key_exists('subset', $config['soliant_payment_authnet'])
            || !array_key_exists('subset_collection', $config['soliant_payment_authnet'])
            || !array_key_exists('subset_parent', $config['soliant_payment_authnet'])
            || !array_key_exists('subset_alias', $config['soliant_payment_authnet'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment is not correctly configured.');
        }

        /** @var RequestHydrator $transactionRequestHydrator */
        $transactionRequestHydrator = $sm->get(TransactionRequestHydrator::class);
        $transactionRequestHydrator->setTransactionRequestType(AuthorizeAndCaptureService::PAYMENT_TRANSACTION_TYPE);

        return new AuthorizeAndCaptureService(
            new TransactionRequestType(),
            $sm->get(CreateTransactionRequest::class),
            $sm->get(TransactionMode::class),
            $transactionRequestHydrator,
            $config['soliant_payment_authnet']['subset'],
            $config['soliant_payment_authnet']['subset_collection'],
            $config['soliant_payment_authnet']['subset_parent'],
            $config['soliant_payment_authnet']['subset_alias']
        );
    }
}
