<?php
namespace Soliant\Payment\Authnet\Payment\Hydrator;

use net\authorize\api\contract\v1\TransactionRequestType;
use Zend\Hydrator\ClassMethods;

class TransactionRequestHydrator extends ClassMethods
{
    /**
     * @var array
     */
    private $serviceConfig = [];

    /**
     * @var string
     */
    private $transactionRequestType;

    /**
     * @param array $serviceConfig
     */
    public function __construct(array $serviceConfig)
    {
        $this->serviceConfig = $serviceConfig;
        parent::__construct();
    }

    /**
     * @param array $data
     * @param object $object
     * @param bool|string $subset
     * @return object
     */
    public function hydrate(array $data, $object, $subset = null)
    {
        if (false !== $subset) {
            $fieldMap = $this->getFieldMap($subset);
            $data = $this->applyFieldMap($data, $fieldMap, array_keys($fieldMap));
        }

        return parent::hydrate($data, $object);
    }

    /**
     * @param string $transactionRequestType
     */
    public function setTransactionRequestType($transactionRequestType)
    {
        $this->transactionRequestType = $transactionRequestType;
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
     * @param null|string $subset
     * @return array
     */
    private function getFieldMap($subset = null)
    {
        $fieldMap = $this->serviceConfig[$this->transactionRequestType]['field_map'];
        return null === $subset ? $fieldMap : $fieldMap[$subset];
    }
}
