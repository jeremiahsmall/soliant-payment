<?php
namespace Soliant\Payment\AuthentTest;

use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Module;

/**
 * @covers \Soliant\Payment\Authnet\Module
 */
class ModuleTest extends TestCase
{
    public function testGetConfig()
    {
        $module = new Module();
        $this->assertArrayHasKey('soliant_payment_authnet', $module->getConfig());
    }
}
