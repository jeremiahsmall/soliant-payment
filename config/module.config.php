<?php
return [
    'service_manager' => [
        'factories' => [
            Soliant\AuthnetPayment\Authnet\Authentication\Authentication::class =>
                Soliant\AuthnetPayment\Authnet\Authentication\Factory\AuthenticationFactory::class,
            Soliant\AuthnetPayment\Authnet\Request\AuthorizeAndCaptureService::class =>
                Soliant\AuthnetPayment\Authnet\Request\Factory\AuthorizeAndCaptureServiceFactory::class,
            Soliant\AuthnetPayment\Authnet\Request\TransactionMode::class =>
                Soliant\AuthnetPayment\Authnet\Request\Factory\TransactionModeFactory::class,
        ],
    ],
    'payment_base' => [
        'services' => [
            'authorizationAndCapture' => \Soliant\AuthnetPayment\Authnet\Request\AuthCaptureRequest::class,
        ]
    ],
];
