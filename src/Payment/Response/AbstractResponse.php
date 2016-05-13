<?php

namespace Soliant\PaymentBase\Payment\Response;

abstract class AbstractResponse
{
    /**
     * @return bool
     */
    abstract protected function isSuccess();

    /**
     * @return array
     */
    abstract protected function getMessages();

    /**
     * @return array
     */
    abstract protected function getData();
}
