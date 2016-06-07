<?php
namespace Soliant\AuthnetPayment\Authnet\Request\Factory;

use Interop\Container\ContainerInterface;
use OutOfBoundsException;
use Soliant\AuthnetPayment\Authnet\Request\TransactionMode;
use Zend\ServiceManager\Factory\FactoryInterface;

class TransactionModeFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        if (!array_key_exists('authnet_payment', $config)
            && array_key_exists('mode', $config['authnet_payment'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication mode not configured.');
        }

        return new TransactionMode($config['authnet_payment']['mode']);
    }
}