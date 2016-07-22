<?php
namespace Soliant\Payment\Authnet\Payment\Hydrator;

use InvalidArgumentException;
use net\authorize\api\contract\v1\CustomerAddressType;
use Zend\Hydrator\HydratorInterface;

class CustomerAddressTypeHydrator implements HydratorInterface
{
    const FIELD_FIRST_NAME = 'firstName';
    const FIELD_LAST_NAME = 'lastName';
    const FIELD_COMPANY = 'company';
    const FIELD_ADDRESS = 'address';
    const FIELD_CITY = 'city';
    const FIELD_STATE = 'state';
    const FIELD_ZIP = 'zip';
    const FIELD_COUNTRY = 'country';
    const FIELD_PHONE_NUMBER = 'phoneNumber';
    const FIELD_FAX_NUMBER = 'faxNumber';

    /**
     * @param CustomerAddressType $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];

        foreach ($this->getFields() as $field) {
            $method = 'set'.ucfirst($field);
            if ('' !== $object->{$method}()) {
                $data[$field] = $object->{$method}();
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @param CustomerAddressType $object
     * @param array|null $fieldMap
     * @return CustomerAddressType
     */
    public function hydrate(array $data, $object, array $fieldMap = null)
    {
        if (!$object instanceof CustomerAddressType) {
            throw new InvalidArgumentException(sprintf(
                'CustomerAddressTypeHydrator expects an object of type CustomerAddressType, got %s',
                is_object($object) ? get_class($object) : gettype($object)
            ));
        }

        foreach ($this->getFields() as $field) {
            if (isset($data[null !== $fieldMap ? $fieldMap[$field] : $field])) {
                $method = 'set' . ucfirst($field);
                $object->{$method}($data[null !== $fieldMap ? $fieldMap[$field] : $field]);
            }
        }

        return $object;
    }

    /**
     * @return array
     */
    protected function getFields()
    {
        return [
            self::FIELD_FIRST_NAME,
            self::FIELD_LAST_NAME,
            self::FIELD_COMPANY,
            self::FIELD_ADDRESS,
            self::FIELD_CITY,
            self::FIELD_STATE,
            self::FIELD_ZIP,
            self::FIELD_COUNTRY,
            self::FIELD_PHONE_NUMBER,
            self::FIELD_FAX_NUMBER,
        ];
    }
}
