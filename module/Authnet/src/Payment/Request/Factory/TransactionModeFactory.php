<?php
namespace Soliant\Payment\Authnet\Payment\Request\Factory;

use OutOfBoundsException;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransactionModeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (!array_key_exists('soliant_payment_authnet', $config)
            || !array_key_exists('mode', $config['soliant_payment_authnet'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication mode not configured.');
        }

        return new TransactionMode($config['soliant_payment_authnet']['mode']);
    }
}
