<?php
namespace Soliant\Payment\Authnet\Payment\Authentication\Factory;

use net\authorize\api\contract\v1\MerchantAuthenticationType;
use OutOfBoundsException;
use Interop\Container\ContainerInterface;

class AuthenticationFactory
{
    public function __invoke(ContainerInterface $sm)
    {
        $config = $sm->get('config');

        if (!array_key_exists('soliant_payment_authnet', $config)
            || !array_key_exists('login', $config['soliant_payment_authnet'])
            || !array_key_exists('key', $config['soliant_payment_authnet'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication key not configured.');
        }

        $merchantAuthentication = new MerchantAuthenticationType();
        $merchantAuthentication->setName($config['soliant_payment_authnet']['login']);
        $merchantAuthentication->setTransactionKey($config['soliant_payment_authnet']['key']);

        return $merchantAuthentication;
    }
}
