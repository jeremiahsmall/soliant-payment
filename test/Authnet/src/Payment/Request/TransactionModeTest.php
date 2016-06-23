<?php

namespace Soliant\Payment\AuthentTest\Payment\Request;

use net\authorize\api\constants\ANetEnvironment;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\Payment\Authnet\Payment\Request\TransactionMode;

/**
 * @covers Soliant\Payment\Authnet\Payment\Request\TransactionMode
 */
class TransactionModeTest extends TestCase
{
    public function testValidTransactionMode()
    {
        $transactionMode = new TransactionMode('sandbox');
        $this->assertEquals(ANetEnvironment::SANDBOX, $transactionMode->getTransactionMode());

        $transactionMode = new TransactionMode('production');
        $this->assertEquals(ANetEnvironment::PRODUCTION, $transactionMode->getTransactionMode());
    }

    public function testInvalidTransactionMode()
    {
        $transactionMode = new TransactionMode('invalid');
        $this->expectException(\DomainException::class);
        $transactionMode->getTransactionMode();
    }
}
