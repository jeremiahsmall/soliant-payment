<?php

namespace Soliant\PaymentBase\Payment;

use Soliant\PaymentBase\Payment\Response\AbstractResponse;

abstract class AbstractRequestService
{
    /**
     * @param array $request
     * @return AbstractResponse
     */
    abstract public function sendRequest(array $request);

    /**
     * @return AbstractResponse
     */
    abstract public function getResponse();
}
