<?php

namespace Soliant\Payment\Base\Payment;

use Soliant\Payment\Base\Payment\Response\AbstractResponse;

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
