<?php
namespace Soliant\Payment\Authnet\Payment\Request;

use net\authorize\api\contract\v1\BankAccountType;
use net\authorize\api\contract\v1\CcAuthenticationType;
use net\authorize\api\contract\v1\CreditCardTrackType;
use net\authorize\api\contract\v1\CreditCardType;
use net\authorize\api\contract\v1\CustomerAddressType;
use net\authorize\api\contract\v1\CustomerDataType;
use net\authorize\api\contract\v1\CustomerProfilePaymentType;
use net\authorize\api\contract\v1\ExtendedAmountType;
use net\authorize\api\contract\v1\LineItemType;
use net\authorize\api\contract\v1\NameAndAddressType;
use net\authorize\api\contract\v1\OrderType;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\contract\v1\SettingType;
use net\authorize\api\contract\v1\SolutionType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\contract\v1\TransRetailInfoType;
use net\authorize\api\contract\v1\UserFieldType;

class SubsetsService
{
    const BILL_TO_SUBSET = 'billTo';
    const SHIP_TO_SUBSET = 'shipTo';
    const LINE_ITEMS_SUBSET = 'lineItems';
    const TAX_SUBSET = 'tax';
    const DUTY_SUBSET = 'duty';
    const SHIPPING_SUBSET = 'shipping';
    const ORDER_SUBSET = 'order';
    const BANK_ACCOUNT_SUBSET = 'bankAccount';
    const CREDIT_CARD_SUBSET = 'creditCard';
    const TRACK_DATA_SUBSET = 'trackData';
    const PROFILE_SUBSET = 'profile';
    const CUSTOMER_SUBSET = 'customer';
    const SOLUTION_SUBSET = 'solution';
    const CARDHOLDER_AUTHENTICATION_SUBSET = 'cardholderAuthentication';
    const RETAIL_SUBSET = 'retail';
    const TRANSACTION_SETTINGS_SUBSET = 'transactionSettings';
    const USER_FIELDS_SUBSET = 'userFields';

    /**
     * @var mixed
     */
    protected $subsetParent;

    /**
     * @var array
     */
    protected $subsets = [
        AuthorizeAndCaptureService::PAYMENT_TRANSACTION_TYPE => [
            self::BILL_TO_SUBSET,
            self::SHIP_TO_SUBSET,
            self::LINE_ITEMS_SUBSET,
            self::TAX_SUBSET,
            self::DUTY_SUBSET,
            self::SHIPPING_SUBSET,
            self::ORDER_SUBSET,
            self::BANK_ACCOUNT_SUBSET,
            self::CREDIT_CARD_SUBSET,
            self::TRACK_DATA_SUBSET,
            self::PROFILE_SUBSET,
            self::CUSTOMER_SUBSET,
            self::SOLUTION_SUBSET,
            self::CARDHOLDER_AUTHENTICATION_SUBSET,
            self::RETAIL_SUBSET,
            self::TRANSACTION_SETTINGS_SUBSET,
            self::USER_FIELDS_SUBSET,
        ],
    ];

    /**
     * @param string $subsetKey
     * @return mixed
     */
    public function getSubsetForKey($subsetKey)
    {
        switch ($subsetKey) {
            case self::BILL_TO_SUBSET:
                return new CustomerAddressType();
                break;
            case self::SHIP_TO_SUBSET:
                return new NameAndAddressType();
                break;
            case self::LINE_ITEMS_SUBSET:
                return new LineItemType();
                break;
            case self::TAX_SUBSET:
            case self::DUTY_SUBSET:
            case self::SHIPPING_SUBSET:
                return new ExtendedAmountType();
                break;
            case self::ORDER_SUBSET:
                return new OrderType();
                break;
            case self::BANK_ACCOUNT_SUBSET:
                return new BankAccountType();
                break;
            case self::CREDIT_CARD_SUBSET:
                return new CreditCardType();
                break;
            case self::TRACK_DATA_SUBSET:
                return new CreditCardTrackType();
                break;
            case self::PROFILE_SUBSET:
                return new CustomerProfilePaymentType();
                break;
            case self::CUSTOMER_SUBSET:
                return new CustomerDataType();
                break;
            case self::SOLUTION_SUBSET:
                return new SolutionType();
                break;
            case self::CARDHOLDER_AUTHENTICATION_SUBSET:
                return new CcAuthenticationType();
                break;
            case self::RETAIL_SUBSET:
                return new TransRetailInfoType();
                break;
            case self::TRANSACTION_SETTINGS_SUBSET:
                return new SettingType();
                break;
            case self::USER_FIELDS_SUBSET:
                return new UserFieldType();
                break;
        }
    }

    /**
     * @param string $subsetKey
     * @param TransactionRequestType $transactionRequestType
     * @param mixed $subset
     * @param mixed $subsetParent
     */
    public function setSubsetForKey(
        $subsetKey,
        TransactionRequestType $transactionRequestType,
        $subset,
        $subsetParent = false
    ) {
        switch ($subsetKey) {
            case self::BILL_TO_SUBSET:
                $transactionRequestType->setBillTo($subset);
                break;
            case self::SHIP_TO_SUBSET:
                $transactionRequestType->setShipTo($subset);
                break;
            case self::LINE_ITEMS_SUBSET:
                $transactionRequestType->addToLineItems($subset);
                break;
            case self::TAX_SUBSET:
                $transactionRequestType->setTax($subset);
                break;
            case self::DUTY_SUBSET:
                $transactionRequestType->setDuty($subset);
                break;
            case self::SHIPPING_SUBSET:
                $transactionRequestType->setShipping($subset);
                break;
            case self::ORDER_SUBSET:
                $transactionRequestType->setOrder($subset);
                break;
            case self::BANK_ACCOUNT_SUBSET:
                $subsetParent->setBankAccount($subset);
                $transactionRequestType->setPayment($subsetParent);
                break;
            case self::CREDIT_CARD_SUBSET:
                $subsetParent->setCreditCard($subset);
                $transactionRequestType->setPayment($subsetParent);
                break;
            case self::TRACK_DATA_SUBSET:
                $subsetParent->setTrackData($subset);
                $transactionRequestType->setPayment($subsetParent);
                break;
            case self::PROFILE_SUBSET:
                $transactionRequestType->setProfile($subset);
                break;
            case self::CUSTOMER_SUBSET:
                $transactionRequestType->setCustomer($subset);
                break;
            case self::SOLUTION_SUBSET:
                $transactionRequestType->setSolution($subset);
                break;
            case self::CARDHOLDER_AUTHENTICATION_SUBSET:
                $transactionRequestType->setCardholderAuthentication($subset);
                break;
            case self::RETAIL_SUBSET:
                $transactionRequestType->setRetail($subset);
                break;
            case self::TRANSACTION_SETTINGS_SUBSET:
                $transactionRequestType->addToTransactionSettings($subset);
                break;
            case self::USER_FIELDS_SUBSET:
                $transactionRequestType->addToUserFields($subset);
                break;
        }
    }

    /**
     * @param string $subsetKey
     * @return bool
     */
    public function subsetHasParent($subsetKey)
    {
        switch ($subsetKey) {
            case self::BANK_ACCOUNT_SUBSET:
            case self::CREDIT_CARD_SUBSET:
            case self::TRACK_DATA_SUBSET:
                return true;
                break;
        }

        return false;
    }

    /**
     * @param string $subsetKey
     * @return bool
     */
    public function getSubsetParent($subsetKey)
    {
        switch ($subsetKey) {
            case self::BANK_ACCOUNT_SUBSET:
            case self::CREDIT_CARD_SUBSET:
            case self::TRACK_DATA_SUBSET:
                if (null === $this->subsetParent) {
                    $this->subsetParent = new PaymentType();
                }
                return $this->subsetParent;
                break;
        }

        return false;
    }

    /**
     * @param string $transactionType
     * @return bool|array
     */
    public function getSubsetsForTransactionType($transactionType)
    {
        if (array_key_exists($transactionType, $this->subsets)) {
            return $this->subsets[$transactionType];
        }

        return false;
    }

    /**
     * @param string $subsetKey
     * @return bool
     */
    public function isSubsetCollection($subsetKey)
    {
        switch ($subsetKey) {
            case self::LINE_ITEMS_SUBSET:
            case self::TRANSACTION_SETTINGS_SUBSET:
            case self::USER_FIELDS_SUBSET:
                return true;
                break;
            default:
                return false;
        }
    }
}
