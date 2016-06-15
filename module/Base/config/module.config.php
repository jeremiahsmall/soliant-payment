<?php

return [
    'service_manager' => [
        'invokables' => [
            Soliant\Payment\Base\Payment\AbstractRequestService::class =>
                Soliant\Payment\Base\Payment\AbstractRequestService::class,
            Soliant\Payment\Base\Payment\Response\AbstractResponse::class =>
                Soliant\Payment\Base\Payment\Response\AbstractResponse::class,
        ],
    ],
];
