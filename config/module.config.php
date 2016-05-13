<?php
return [
    'service_manager' => [
        'factories' => [
            'Soliant\AuthnetPayment\Authnet\Authentication\Authentication' 
            => \Soliant\AuthnetPayment\Authnet\Authentication\Factory\AuthenticationFactory::class,
            \Soliant\AuthnetPayment\Authnet\Request\AuthCaptureRequest::class
            => \Soliant\AuthnetPayment\Authnet\Request\Factory\AuthCaptureRequestFactory::class,
            \Soliant\AuthnetPayment\Authnet\Request\TransactionMode::class
            => \Soliant\AuthnetPayment\Authnet\Request\Factory\TransactionModeFactory::class,
        ],
    ],
    'payment_base' => [
        'services' => [
            'authorizationAndCapture' => \Soliant\AuthnetPayment\Authnet\Request\AuthCaptureRequest::class,
        ]
    ],
];
