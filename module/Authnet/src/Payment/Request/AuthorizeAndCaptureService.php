<?php
namespace Soliant\Payment\Authnet\Payment\Request;

use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\CreateTransactionController;
use Soliant\Payment\Authnet\Payment\Response\AuthCaptureResponse;
use Soliant\Payment\Base\Payment\AbstractRequestService;
use Zend\Hydrator\ClassMethods;

class AuthorizeAndCaptureService extends AbstractRequestService
{
    const PAYMENT_TRANSACTION_TYPE = 'authCaptureTransaction';

    /**
     * @var CreateTransactionRequest
     */
    protected $createTransactionRequest;

    /**
     * @var TransactionMode
     */
    protected $transactionMode;

    /**
     * @var AuthCaptureResponse
     */
    protected $authCaptureResponse;

    /**
     * @var array
     */
    protected $fieldMap;

    /**
     * @var ClassMethods
     */
    protected $hydrator;

    /**
     * @var TransactionRequestType
     */
    protected $transactionRequestType;

    /**
     * @var SubsetsService
     */
    protected $subsetsService;

    /**
     * @param CreateTransactionRequest $createTransactionRequest
     * @param TransactionMode $transactionMode
     * @param array $fieldMap
     * @param ClassMethods $hydrator
     * @param TransactionRequestType $transactionRequestType
     * @param SubsetsService $subsetsService
     */
    public function __construct(
        CreateTransactionRequest $createTransactionRequest,
        TransactionMode $transactionMode,
        array $fieldMap,
        ClassMethods $hydrator,
        TransactionRequestType $transactionRequestType,
        SubsetsService $subsetsService
    ) {
        $this->createTransactionRequest = $createTransactionRequest;
        $this->transactionMode = $transactionMode;
        $this->fieldMap = $fieldMap;
        $this->hydrator = $hydrator;
        $this->transactionRequestType = $transactionRequestType;
        $this->subsetsService = $subsetsService;
    }

    /**
     * @param array $data
     * @return AuthCaptureResponse
     * @throws Exception
     */
    public function sendRequest(array $data)
    {
        /**
         * Set base transaction type data
         */
        $this->transactionRequestType->setTransactionType(self::PAYMENT_TRANSACTION_TYPE);
        $this->hydrator->hydrate(
            $this->applyFieldMap($data, $this->fieldMap, array_keys($this->fieldMap)),
            $this->transactionRequestType
        );

        /**
         * Add subsets
         */
        foreach ($this->subsetsService->getSubsetsForTransactionType(self::PAYMENT_TRANSACTION_TYPE) as $subsetKey) {

            if (array_key_exists($subsetKey, $data) && is_array($data[$subsetKey])) {
                
                if ($this->subsetsService->isSubsetCollection($subsetKey)) {

                    foreach ($data[$subsetKey] as $subsetData) {
                        $this->addSubsetForKey($subsetKey, $subsetData);
                    }
                } elseif ($this->subsetsService->subsetHasParent($subsetKey)) {

                    $this->addSubsetForKey(
                        $subsetKey,
                        $data[$subsetKey],
                        $this->subsetsService->getSubsetParent($subsetKey)
                    );
                } else {
                    $this->addSubsetForKey($subsetKey, $data[$subsetKey]);
                }
            }
        }

        $this->createTransactionRequest->setTransactionRequest($this->transactionRequestType);

        $controller = new CreateTransactionController($this->createTransactionRequest);
        $response = $controller->executeWithApiResponse($this->transactionMode->getTransactionMode());

        $this->authCaptureResponse = new AuthCaptureResponse($response);
        return $this->authCaptureResponse;
    }

    /**
     * @return AuthCaptureResponse
     */
    public function getResponse()
    {
        return $this->authCaptureResponse;
    }

    /**
     * @param array $data
     * @param array $map
     * @param array $available
     * @return array $mapped
     */
    private function applyFieldMap(array $data, array $map, array $available)
    {
        $mapped = null;
        
        foreach ($available as $field) {
            if (!is_array($map[$field]) && isset($data[$map[$field]])) {
                $mapped[$field] = $data[$map[$field]];
            }
        }

        return $mapped;
    }

    /**
     * @param string $subsetKey
     * @param array $data
     * @param mixed $subsetParent
     */
    private function addSubsetForKey($subsetKey, array $data, $subsetParent = null)
    {
        $subset = $this->subsetsService->getSubsetForKey($subsetKey);

        $this->hydrator->hydrate(
            $this->applyFieldMap(
                $data,
                $this->fieldMap[$subsetKey],
                array_keys($this->fieldMap[$subsetKey])
            ),
            $subset
        );

        $this->subsetsService->setSubsetForKey(
            $subsetKey,
            $this->transactionRequestType,
            $subset,
            null === $subsetParent ? null : $subsetParent
        );
    }
}
