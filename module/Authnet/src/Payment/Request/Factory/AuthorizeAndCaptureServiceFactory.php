<?php
namespace Soliant\Payment\Authnet\Payment\Request\Factory;

use net\authorize\api\contract\v1\MerchantAuthenticationType;
use OutOfBoundsException;
use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorizeAndCaptureServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (!array_key_exists('soliant_payment_authnet', $config)
            || !array_key_exists('service', $config['soliant_payment_authnet'])
            || !array_key_exists('authorizationAndCapture', $config['soliant_payment_authnet']['service'])
            || !array_key_exists('field_map', $config['soliant_payment_authnet']['service']['authorizationAndCapture'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication mode not configured.');
        }

        return new AuthorizeAndCaptureService(
            $serviceLocator->get(MerchantAuthenticationType::class),
            $serviceLocator->get(TransactionMode::class),
            $config['soliant_payment_authnet']['service']['authorizationAndCapture']['field_map']
        );
    }
}
