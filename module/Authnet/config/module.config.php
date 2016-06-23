<?php
return [
    'service_manager' => [
        'aliases' => [
            'authorizeAndCapture' => Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService::class,
        ],
        'factories' => [
            net\authorize\api\contract\v1\MerchantAuthenticationType::class =>
                Soliant\Payment\Authnet\Payment\Authentication\Factory\AuthenticationFactory::class,
            Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService::class =>
                Soliant\Payment\Authnet\Payment\Request\Factory\AuthorizeAndCaptureServiceFactory::class,
            Soliant\Payment\Authnet\Payment\Request\TransactionMode::class =>
                Soliant\Payment\Authnet\Payment\Request\Factory\TransactionModeFactory::class,
        ],
    ],
    'soliant_payment_authnet' => [
        'service' => [
            'authorizationAndCapture' => [
                'field_map' => [
                    'paymentType' => 'paymentType', // (eCheck, creditCard)
                    'cardNumber' => 'cardNumber',
                    'expirationDate' => 'expirationDate',
                    'amount' => 'amount',
                ],
            ],
        ],
    ],
];
