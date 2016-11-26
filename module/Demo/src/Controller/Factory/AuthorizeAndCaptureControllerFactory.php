<?php
namespace Soliant\Payment\Demo\Controller\Factory;

use Soliant\Payment\Demo\Controller\AuthorizeAndCaptureController;
use Interop\Container\ContainerInterface;

class AuthorizeAndCaptureControllerFactory
{
    public function __invoke(ContainerInterface $sm)
    {
        return new AuthorizeAndCaptureController($sm->get('authorizeAndCapture'));
    }
}
