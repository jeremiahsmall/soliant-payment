<?php

return [
    'router' => [
        'routes' => [],
    ],
    'service_manager' => [
        'abstract_factories' => [
            \Soliant\PaymentBase\Payment\Service\AbstractPaymentServiceFactory::class,
        ],
        'invokables' => [
            Soliant\PaymentBase\Payment\AbstractRequestService::class =>
                Soliant\PaymentBase\Payment\AbstractRequestService::class,
            Soliant\PaymentBase\Payment\Response\AbstractResponse::class =>
                Soliant\PaymentBase\Payment\Response\AbstractResponse::class,
        ],
        'factories' => [],
    ],
    'controllers' => [
        'invokables' => [],
        'factories' => [],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'view_helpers' => [],
];
