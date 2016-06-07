<?php
namespace Soliant\AuthnetPayment\Authnet\Request\Factory;

use OutOfBoundsException;
use Soliant\AuthnetPayment\Authnet\Authentication\Authentication;
use Soliant\AuthnetPayment\Authnet\Request\AuthorizeAndCaptureService;
use Soliant\AuthnetPayment\Authnet\Request\TransactionMode;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorizeAndCaptureServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!array_key_exists('authnet_payment', $config)
            && array_key_exists('service', $config['authnet_payment'])
            && array_key_exists('authorizationAndCapture', $config['authnet_payment']['service'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication mode not configured.');
        }

        return new AuthorizeAndCaptureService(
            $serviceLocator->get(Authentication::class),
            $serviceLocator->get(TransactionMode::class),
            $serviceLocator->get($config['authnet_payment']['service']['authorizationAndCapture']['field_map'])
        );
    }
}
