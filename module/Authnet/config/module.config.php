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
    ],
    'hydrators' => [
        'factories' => [
            Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator::class =>
                Soliant\Payment\Authnet\Payment\Hydrator\Factory\TransactionRequestHydratorFactory::class,
        ],
    ],
    'soliant_payment_authnet' => [
        'subset' => [
            'billTo' => net\authorize\api\contract\v1\CustomerAddressType::class,
            'shipTo' => net\authorize\api\contract\v1\NameAndAddressType::class,
            'lineItems' => net\authorize\api\contract\v1\LineItemType::class,
            'tax' => net\authorize\api\contract\v1\ExtendedAmountType::class,
            'duty' => net\authorize\api\contract\v1\ExtendedAmountType::class,
            'shipping' => net\authorize\api\contract\v1\ExtendedAmountType::class,
            'order' => net\authorize\api\contract\v1\OrderType::class,
            'bankAccount' => net\authorize\api\contract\v1\BankAccountType::class,
            'creditCard' => net\authorize\api\contract\v1\CreditCardType::class,
            'trackData' => net\authorize\api\contract\v1\CreditCardTrackType::class,
            'profile' => net\authorize\api\contract\v1\CustomerProfilePaymentType::class,
            'customer' => net\authorize\api\contract\v1\CustomerDataType::class,
            'solution' => net\authorize\api\contract\v1\SolutionType::class,
            'cardholderAuthentication' => net\authorize\api\contract\v1\CcAuthenticationType::class,
            'retail' => net\authorize\api\contract\v1\TransRetailInfoType::class,
            'transactionSettings' => net\authorize\api\contract\v1\SettingType::class,
            'userFields' => net\authorize\api\contract\v1\UserFieldType::class,
        ],
        'subset_collection' => [
            'lineItems',
            'userFields',
            'transactionSettings',
        ],
        'subset_parent' => [
            'bankAccount' =>  net\authorize\api\contract\v1\PaymentType::class,
            'trackData' =>  net\authorize\api\contract\v1\PaymentType::class,
            'creditCard' =>  net\authorize\api\contract\v1\PaymentType::class,
        ],
        'subset_alias' => [
            'bankAccount' => 'payment',
            'trackData' => 'payment',
            'creditCard' => 'payment',
        ],
        'service' => [
            'authCaptureTransaction' => [
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
                    'cardholderAuthentication' => [
                        'authenticationIndicator' => 'authenticationIndicator',
                        'cardholderAuthenticationValue' => 'cardholderAuthenticationValue',
                    ],
                    'retail' => [
                        'marketType' => 'marketType',
                        'deviceType' => 'deviceType',
                    ],
                    'transactionSettings' => [
                        'settingName' => 'settingName',
                        'settingValue' => 'settingValue',
                    ],
                    'userFields' => [
                        'name' => 'name',
                        'value' => 'value',
                    ],
                ],
            ],
        ],
    ],
];
