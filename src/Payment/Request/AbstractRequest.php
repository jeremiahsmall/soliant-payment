<?php

namespace Soliant\PaymentBase\Payment\Request;

use Soliant\PaymentBase\Payment\Response\AbstractResponse;

abstract class AbstractRequest
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
