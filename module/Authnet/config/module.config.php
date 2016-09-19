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
            net\authorize\api\contract\v1\CreateTransactionRequest::class =>
                Soliant\Payment\Authnet\Payment\Request\Factory\CreateTransactionRequestFactory::class,
        ],
        'invokables' => [
            Soliant\Payment\Authnet\Payment\Request\CreditCardType::class =>
                Soliant\Payment\Authnet\Payment\Request\CreditCardType::class,
            Soliant\Payment\Authnet\Payment\Request\SubsetsService::class =>
                Soliant\Payment\Authnet\Payment\Request\SubsetsService::class,
        ],
    ],
    'soliant_payment_authnet' => [
        'service' => [
            'authorizationAndCapture' => [
                'field_map' => [
                    'amount' => 'amount',
                    'trackData' => [
                        'track1' => 'track1',
                        'track2' => 'track2',
                    ],
                    'creditCard' => [
                        'cardNumber' => 'cardNumber',
                        'expirationDate' => 'expirationDate',
                        'cardCode' => 'cardCode',
                    ],
                    'bankAccount' => [
                        'accountNumber' => 'accountNumber',
                        'routingNumber' => 'routingNumber',
                        'nameOnAccount' => 'nameOnAccount',
                        'accountType' => 'accountType',
                        'echeckType' => 'echeckType',
                        'checkNumber' => 'checkNumber',
                        'bankName' => 'bankName',
                    ],
                    'profile' => [
                        'createProfile' => 'createProfile', // boolean
                    ],
                    'solution' => [
                        'id' => 'id',
                    ],
                    'order' => [
                        'invoiceNumber' => 'invoiceNumber',
                        'description' => 'description',
                    ],
                    'lineItems' => [
                        'itemId' => 'itemId',
                        'name' => 'name',
                        'description' => 'description',
                        'quantity' => 'quantity',
                        'unitPrice' => 'unitPrice',
                        'taxable' => 'taxable',
                    ],
                    'tax' => [
                        'amount' => 'amount',
                        'name' => 'name',
                        'description' => 'description',
                    ],
                    'duty' => [
                        'amount' => 'amount',
                        'name' => 'name',
                        'description' => 'description',
                    ],
                    'shipping' => [
                        'amount' => 'amount',
                        'name' => 'name',
                        'description' => 'description',
                    ],
                    'taxExempt' => 'taxExempt',
                    'poNumber' => 'poNumber',
                    'customer' => [
                        'type' => 'type', // (individual, business)
                        'id' => 'id',
                        'email' => 'email',
                    ],
                    'billTo' => [
                        'firstName' => 'firstName',
                        'lastName' => 'lastName',
                        'company' => 'company',
                        'address' => 'address',
                        'city' => 'city',
                        'state' => 'state',
                        'zip' => 'zip',
                        'country' => 'country',
                        'phoneNumber' => 'phoneNumber',
                        'faxNumber' => 'faxNumber',
                        'email' => 'email'
                    ],
                    'shipTo' => [
                        'firstName' => 'firstName',
                        'lastName' => 'lastName',
                        'company' => 'company',
                        'address' => 'address',
                        'city' => 'city',
                        'state' => 'state',
                        'zip' => 'zip',
                        'country' => 'country',
                    ],
                    'employeeId' => 'employeeId',
                    'customerIP' => 'customerIP',
                ],
            ],
        ],
    ],
];
