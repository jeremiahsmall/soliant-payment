<?php

namespace Soliant\PaymentBase\Payment\Response;

abstract class AbstractResponse
{
    /**
     * @return bool
     */
    abstract public function isSuccess();

    /**
     * @return array
     */
    abstract public function getMessages();

    /**
     * @return array
     */
    abstract public function getData();
}
