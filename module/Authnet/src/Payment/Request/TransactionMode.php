<?php
namespace Soliant\Payment\Authnet\Payment\Request;

use net\authorize\api\constants\ANetEnvironment;
use DomainException;

class TransactionMode
{
    const MODE_PRODUCTION = 'production';
    const MODE_SANDBOX = 'sandbox';

    /**
     * @var string
     */
    protected $mode;

    /**
     * TransactionMode constructor.
     *
     * @param $mode
     */
    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    public function getTransactionMode()
    {
        switch ($this->mode) {
            case self::MODE_PRODUCTION:
                return ANetEnvironment::PRODUCTION;
                break;
            case self::MODE_SANDBOX:
                return ANetEnvironment::SANDBOX;
                break;
            default:
                throw new DomainException(
                    'Invalid transaction mode specified.  Must be "production" or "sandbox". '
                    . $this->mode
                    . ' provided.'
                );
        }
    }
}
