<?php
namespace Soliant\AuthnetPayment\Authnet\Authentication\Factory;

use Interop\Container\ContainerInterface;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use OutOfBoundsException;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthenticationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        if (!array_key_exists('authnet_payment', $config)
            && array_key_exists('login', $config['authnet_payment'])
            && array_key_exists('key', $config['authnet_payment'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication key not configured.');
        }

        $merchantAuthentication = new MerchantAuthenticationType();
        $merchantAuthentication->setName($config['authnet_payment']['login']);
        $merchantAuthentication->setTransactionKey($config['authnet_payment']['key']);

        return $merchantAuthentication;
    }
}