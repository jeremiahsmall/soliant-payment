<?php
namespace Soliant\AuthnetPayment\Authnet\Request\Factory;

use OutOfBoundsException;
use Soliant\AuthnetPayment\Authnet\Request\TransactionMode;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransactionModeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!array_key_exists('authnet_payment', $config)
            && array_key_exists('mode', $config['authnet_payment'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication mode not configured.');
        }

        return new TransactionMode($config['authnet_payment']['mode']);
    }
}