# Soliant Payment

[![Build Status](https://travis-ci.org/soliantconsulting/soliant-payment.svg?branch=master)](https://travis-ci.org/soliantconsulting/soliant-payment)
[![Coverage Status](https://coveralls.io/repos/github/soliantconsulting/soliant-payment/badge.svg?branch=master)](https://coveralls.io/github/soliantconsulting/soliant-payment?branch=master)
[![Latest Stable Version](https://poser.pugx.org/soliantconsulting/soliant-payment/v/stable)](https://packagist.org/packages/soliantconsulting/soliant-payment)
[![Latest Unstable Version](https://poser.pugx.org/soliantconsulting/soliant-payment/v/unstable)](https://packagist.org/packages/soliantconsulting/soliant-payment)
[![Total Downloads](https://poser.pugx.org/soliantconsulting/soliant-payment/downloads)](https://packagist.org/packages/soliantconsulting/soliant-payment)
[![License](https://poser.pugx.org/soliantconsulting/soliant-payment/license)](https://packagist.org/packages/soliantconsulting/soliant-payment)

## Quickstart

### Installation

Via composer

```
{
    "require": {
        "soliantconsulting/soliant-payment": "^2.0.0" // ZF2 v1.0.1
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/goetas/serializer.git"
        }
    ],
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

### Usage

ZF3 v2.0.0

Add modules to modules.config.php

```
return [
    'Zend\Filter',
    'Zend\Hydrator',
    'Zend\Router',
    'Zend\Validator',
    'Soliant\Payment\Base',
    'Soliant\Payment\Authnet',
    'Soliant\Payment\Demo', // (Optional) Access demo via /soliant-payment route
];
```

ZF2 v1.0.1

Add modules to project config/application.config.php.

```
'modules' => [
    'Soliant\Payment\Base', // Required for all payment modules
    'Soliant\Payment\[Payment Module]', // Where "Payment Module" is one of the following ("Authnet")
    'Soliant\Payment\Demo', // Access demo via /soliant-payment route
 ],
```

Copy the local.php.dist file from the payment module config directory to the project autoload directory. 

```bash
$ cp vendor/soliantconsulting/soliant-payment/module/[Payment Module]/[Payment Module].payment.local.php.dist 
config/autoload/[Payment Module].payment.local.php
```

Inject the desired payment service via factory using one of the following available aliases. 

"authorizeAndCapture" // Authorize and capture credit card or eCheck 

ZF3 v2.0.0

```
public function __invoke(ContainerInterface $sm)
{
    return new MyService($sm->get("[Service Alias]"));
}
```

ZF2 v1.0.1

```
public function createService(ServiceLocatorInterface $serviceLocator)
{
    return new MyService($serviceLocator->get("[Service Alias]"));
}
```

Each payment service should implement the following request structure.  Request data is passed via array to the 
"sendRequest" method which returns a response object. (See the implemented payment modules payment.local.php.dist file 
for data array structure and info on overriding data field names. Ex. [a link](https://github.com/soliantconsulting/soliant-payment/blob/master/module/Authnet/config/authnet.payment.local.php.dist)).  The response object can be tested for success with 
the "isSuccess" method which returns a boolean response.  

If the request was successful, any data returned from the requested service should be access via the "getData" method.  
The "getMessages" method will be populated in the event the request was unsuccessful.  

```
$response = $this->[Service Alias]->sendRequest([
    'paymentType' => 'creditCard',
    'amount' => '5.00',
    'expirationDate' => '2017-01',
    'cardNumber' => '4111111111111111'
]);

if ($response->isSuccess()) {
    // get the response data
    $data = $response->getData();
} else {
    // get the errors
    $errors = $response->getMessages();
}
```