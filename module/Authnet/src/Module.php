<?php
namespace Soliant\Payment\Authnet;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
