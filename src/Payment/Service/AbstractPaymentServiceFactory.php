<?php

namespace Soliant\PaymentBase\Payment\Service;

use RuntimeException;
use Soliant\PaymentBase\Payment\Request\AbstractRequest;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractPaymentServiceFactory implements AbstractFactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->get('config');
        if (!array_key_exists('payment_base', $config) ||
            !array_key_exists('services', $config['payment_base']) ||
            !array_key_exists($requestedName, $config['payment_base']['services'])
        ) {
            return false;
        }

        return (class_exists($config['payment_base']['services'][$requestedName]));
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return array|object
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->get('config');

        $requestedClassName = $config['payment_base']['services'][$requestedName];
        $requestedClass = $serviceLocator->get($requestedClassName);

        if (!($requestedClass instanceof AbstractRequest)) {
            throw new RuntimeException(sprintf(
                '"%s" must be an instance of AbstractRequest',
                $requestedClassName
            ));
        }

        return $requestedClass;
    }
}
