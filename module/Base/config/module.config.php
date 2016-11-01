<?php

return [
    'service_manager' => [
        'invokables' => [
            Soliant\Payment\Base\Payment\RequestServiceInterface::class =>
                Soliant\Payment\Base\Payment\RequestServiceInterface::class,
            Soliant\Payment\Base\Payment\Response\ResponseInterface::class =>
                Soliant\Payment\Base\Payment\Response\ResponseInterface::class,
        ],
    ],
];
