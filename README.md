# Soliant Payment

[![Build Status](https://travis-ci.org/soliantconsulting/SimpleFM.svg?branch=master)](https://travis-ci.org/soliantconsulting/SimpleFM)
[![Coverage Status](https://coveralls.io/repos/github/soliantconsulting/soliant-payment/badge.svg?branch=master)](https://coveralls.io/github/soliantconsulting/soliant-payment?branch=master)

## Quickstart

### Installation

Via composer

```bash
$ composer require soliantconsulting/soliant-payment
```

### Usage

Add modules to project config/application.config.php.

```
'modules' => [
    'Soliant\Payment\Base', // Required for all payment modules
    'Soliant\Payment\[Payment Module]', // Where "Payment Module" is one of the following (Authnet)
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

```
public function createService(ServiceLocatorInterface $serviceLocator)
{
    return new MyService($serviceLocator->get("[Service Alias]"));
}
```

Each payment service should implement the following request structure.  Request data is passed via array to the 
"sendRequest" method which returns a response object. (See the implemented payment modules payment.local.php.dist file 
for data array structure and info on overriding data field names).  The response object can be tested for success with 
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