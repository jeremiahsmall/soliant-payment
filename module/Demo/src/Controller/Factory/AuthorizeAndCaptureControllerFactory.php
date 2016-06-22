<?php
namespace Soliant\Payment\Demo\Controller\Factory;

use Soliant\Payment\Demo\Controller\AuthorizeAndCaptureController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorizeAndCaptureControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        return new AuthorizeAndCaptureController($serviceLocator->get('authorizeAndCapture'));
    }
}
