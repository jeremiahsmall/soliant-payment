<?php

return [
    'router' => [
        'routes' => [],
    ],
    'service_manager' => [
        'invokables' => [
            Soliant\PaymentBase\Payment\Request\AbstractRequest::class =>
                Soliant\PaymentBase\Payment\Request\AbstractRequest::class,
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
