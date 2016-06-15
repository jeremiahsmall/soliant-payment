<?php
return [
    'router' => [
        'routes' => [
            'authorize-and-capture' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/soliant-payment/authorize-and-capture',
                    'defaults' => [
                        'controller' => \Soliant\Payment\Demo\Controller\AuthorizeAndCaptureController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'soliant-payment-demo' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/soliant-payment',
                    'defaults' => [
                        'controller' => \Soliant\Payment\Demo\Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            \Soliant\Payment\Demo\Controller\IndexController::class
                => \Soliant\Payment\Demo\Controller\IndexController::class,
        ],
        'factories' => [
            \Soliant\Payment\Demo\Controller\AuthorizeAndCaptureController::class
            => \Soliant\Payment\Demo\Controller\Factory\AuthorizeAndCaptureControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/demo/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
