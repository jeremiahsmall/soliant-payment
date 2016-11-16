<?php
namespace Soliant\Payment\Authnet\Payment\Hydrator\Factory;

use OutOfBoundsException;
use Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransactionRequestHydratorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $hydratorManager)
    {
        $serviceLocator = $hydratorManager->getServiceLocator();
        $config = $serviceLocator->get('config');

        if (!array_key_exists('soliant_payment_authnet', $config)
            || !array_key_exists('service', $config['soliant_payment_authnet'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment is not correctly configured.');
        }

        return new TransactionRequestHydrator($config['soliant_payment_authnet']['service']);
    }
}
