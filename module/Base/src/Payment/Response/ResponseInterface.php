<?php

namespace Soliant\Payment\Base\Payment\Response;

interface ResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccess();

    /**
     * @return array
     */
    public function getMessages();

    /**
     * @return array
     */
    public function getData();
}
