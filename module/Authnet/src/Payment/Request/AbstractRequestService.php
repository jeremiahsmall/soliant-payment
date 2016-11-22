<?php
namespace Soliant\Payment\Authnet\Payment\Request;

use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\TransactionRequestType;
use Soliant\Payment\Authnet\Payment\Hydrator\TransactionRequestHydrator;
use Soliant\Payment\Base\Payment\RequestServiceInterface;

abstract class AbstractRequestService implements RequestServiceInterface
{
    /**
     * @var TransactionRequestType
     */
    protected $transactionRequestType;

    /**
     * @var CreateTransactionRequest
     */
    protected $createTransactionRequest;

    /**
     * @var TransactionMode
     */
    protected $transactionMode;

    /**
     * @var TransactionRequestHydrator
     */
    protected $transactionRequestHydrator;

    /**
     * @var array
     */
    protected $subset;

    /**
     * @var array
     */
    protected $subsetCollection;

    /**
     * @var array
     */
    protected $subsetParent;

    /**
     * @var array
     */
    protected $subsetAlias;

    /**
     * @param TransactionRequestType $transactionRequestType
     * @param CreateTransactionRequest $createTransactionRequest
     * @param TransactionMode $transactionMode
     * @param TransactionRequestHydrator $transactionRequestHydrator
     * @param array $subset
     * @param array $subsetCollection
     * @param array $subsetParent
     * @param array $subsetAlias
     */
    public function __construct(
        TransactionRequestType $transactionRequestType,
        CreateTransactionRequest $createTransactionRequest,
        TransactionMode $transactionMode,
        TransactionRequestHydrator $transactionRequestHydrator,
        array $subset = [],
        array $subsetCollection = [],
        array $subsetParent = [],
        array $subsetAlias = []
    ) {
        $this->transactionRequestType = $transactionRequestType;
        $this->createTransactionRequest = $createTransactionRequest;
        $this->transactionMode = $transactionMode;
        $this->transactionRequestHydrator = $transactionRequestHydrator;
        $this->subset = $subset;
        $this->subsetCollection = $subsetCollection;
        $this->subsetParent = $subsetParent;
        $this->subsetAlias = $subsetAlias;
    }

    /**
     * @param array $data
     */
    protected function hydrate(array $data)
    {
        $this->transactionRequestHydrator->hydrate($data, $this->transactionRequestType);
        $transactionRequestTypeData = [];

        /** Loop the configured subsets */
        foreach ($this->subset as $subsetKey => $subsetDataClass) {
            if (array_key_exists($subsetKey, $data) && is_array($data[$subsetKey])) {
                if (in_array($subsetKey, $this->subsetCollection)) {
                    /** Hydrate each object in the collection */
                    foreach ($data[$subsetKey] as $collection) {
                        $subset = new $subsetDataClass;
                        $this->transactionRequestHydrator->hydrate($collection, $subset, $subsetKey);
                        $transactionRequestTypeData[$subsetKey][] = $subset;
                    }
                } elseif (array_key_exists($subsetKey, $this->subsetParent)) {
                    /** Hydrate the subset */
                    $subset = new $subsetDataClass;
                    $this->transactionRequestHydrator->hydrate($data[$subsetKey], $subset, $subsetKey);
                    $subsetKeyAlias = $subsetKey;

                    /** Check to see if an alias is defined */
                    if (array_key_exists($subsetKey, $this->subsetAlias)) {
                        $subsetKeyAlias = $this->subsetAlias[$subsetKey];
                    }

                    /** Some subsets share a parent, pull the parent if it already exists */
                    if (array_key_exists($subsetKeyAlias, $transactionRequestTypeData)) {
                        $subsetParent = $transactionRequestTypeData[$subsetKeyAlias];
                    } else {
                        $subsetParent = new $this->subsetParent[$subsetKey];
                    }

                    $this->transactionRequestHydrator->hydrate([$subsetKey => $subset], $subsetParent, false);
                    $transactionRequestTypeData[$subsetKeyAlias] = $subsetParent;
                } else {
                    /** Hydrate the subset */
                    $subset = new $subsetDataClass;
                    $this->transactionRequestHydrator->hydrate($data[$subsetKey], $subset, $subsetKey);

                    /** Build the data set to be passed to the TransactionRequestType hydrator */
                    $transactionRequestTypeData[$subsetKey] = $subset;
                }
            }
        }

        $this->transactionRequestHydrator->hydrate($transactionRequestTypeData, $this->transactionRequestType, false);
        $this->createTransactionRequest->setTransactionRequest($this->transactionRequestType);
    }
}
