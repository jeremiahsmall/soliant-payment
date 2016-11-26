<?php
namespace Soliant\Payment\Authnet\Payment\Request\Factory;

use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use Interop\Container\ContainerInterface;

class CreateTransactionRequestFactory
{
    public function __invoke(ContainerInterface $sm)
    {
        $createTransactionRequest = new CreateTransactionRequest();
        $createTransactionRequest->setMerchantAuthentication($sm->get(MerchantAuthenticationType::class));
        return $createTransactionRequest;
    }
}
