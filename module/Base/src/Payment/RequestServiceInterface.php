<?php

namespace Soliant\Payment\Base\Payment;

use Soliant\Payment\Base\Payment\Response\ResponseInterface;

interface RequestServiceInterface
{
    /**
     * @param array $request
     * @return ResponseInterface
     */
    public function sendRequest(array $request);

    /**
     * @return ResponseInterface
     */
    public function getResponse();
}
