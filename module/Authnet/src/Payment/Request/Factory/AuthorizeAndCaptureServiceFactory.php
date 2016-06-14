<?php
namespace Soliant\Payment\Authnet\Payment\Request\Factory;

use OutOfBoundsException;
use Soliant\Payment\Authnet\Payment\Authentication\Factory\AuthenticationFactory;
use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorizeAndCaptureServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!array_key_exists('soliant_payment_authnet', $config)
            && array_key_exists('service', $config['soliant_payment_authnet'])
            && array_key_exists('authorizationAndCapture', $config['soliant_payment_authnet']['service'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication mode not configured.');
        }

        return new AuthorizeAndCaptureService(
            $serviceLocator->get(AuthenticationFactory::class),
            $serviceLocator->get(TransactionMode::class),
            $config['soliant_payment_authnet']['service']['authorizationAndCapture']['field_map']
        );
    }
}
