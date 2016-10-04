<?php
namespace Soliant\Payment\Authnet\Payment\Request\Factory;

use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CreateTransactionRequestFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $createTransactionRequest = new CreateTransactionRequest();
        $createTransactionRequest->setMerchantAuthentication($serviceLocator->get(MerchantAuthenticationType::class));
        return $createTransactionRequest;
    }
}
