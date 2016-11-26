<?php
namespace Soliant\Payment\Base;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'invokables' => [
            Soliant\Payment\Base\Payment\RequestServiceInterface::class => InvokableFactory::class,
            Soliant\Payment\Base\Payment\Response\ResponseInterface::class => InvokableFactory::class,
        ],
    ],
];
