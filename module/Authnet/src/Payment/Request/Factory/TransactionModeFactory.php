<?php
namespace Soliant\Payment\Authnet\Payment\Request\Factory;

use OutOfBoundsException;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Interop\Container\ContainerInterface;

class TransactionModeFactory
{
    public function __invoke(ContainerInterface $sm)
    {
        $config = $sm->get('config');

        if (!array_key_exists('soliant_payment_authnet', $config)
            || !array_key_exists('mode', $config['soliant_payment_authnet'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication mode not configured.');
        }

        return new TransactionMode($config['soliant_payment_authnet']['mode']);
    }
}
