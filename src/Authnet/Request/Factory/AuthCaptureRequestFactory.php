<?php
namespace Soliant\AuthnetPayment\Authnet\Request\Factory;

use Soliant\AuthnetPayment\Authnet\Request\AuthCaptureRequest;
use Soliant\AuthnetPayment\Authnet\Request\TransactionMode;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthCaptureRequestFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AuthCaptureRequest(
            $serviceLocator->get('Soliant\AuthnetPayment\Authnet\Authentication\Authentication'),
            $serviceLocator->get(TransactionMode::class)
        );
    }
}
