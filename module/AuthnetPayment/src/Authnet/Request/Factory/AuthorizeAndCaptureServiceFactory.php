<?php
namespace Soliant\AuthnetPayment\Authnet\Request\Factory;

use Interop\Container\ContainerInterface;
use OutOfBoundsException;
use Soliant\AuthnetPayment\Authnet\Authentication\Factory\AuthenticationFactory;
use Soliant\AuthnetPayment\Authnet\Request\AuthorizeAndCaptureService;
use Soliant\AuthnetPayment\Authnet\Request\TransactionMode;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthorizeAndCaptureServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        if (!array_key_exists('authnet_payment', $config)
            && array_key_exists('service', $config['authnet_payment'])
            && array_key_exists('authorizationAndCapture', $config['authnet_payment']['service'])
        ) {
            throw new OutOfBoundsException('AuthnetPayment authentication mode not configured.');
        }

        return new AuthorizeAndCaptureService(
            $container->get(AuthenticationFactory::class),
            $container->get(TransactionMode::class),
            $config['authnet_payment']['service']['authorizationAndCapture']['field_map']
        );
    }
}
