<?php
namespace Soliant\Payment\Demo\Controller;

use Soliant\Payment\Authnet\Payment\Request\AuthorizeAndCaptureService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthorizeAndCaptureController extends AbstractActionController
{
    /**
     * @var AuthorizeAndCaptureService
     */
    private $authorizeAndCaptureService;

    /**
     * @param AuthorizeAndCaptureService $authorizeAndCaptureService
     */
    public function __construct(AuthorizeAndCaptureService $authorizeAndCaptureService)
    {
        $this->authorizeAndCaptureService = $authorizeAndCaptureService;
    }

    public function indexAction()
    {
        $response = null;
        $data = null;

        if ($this->getRequest()->isPost()) {
            $response = $this->authorizeAndCaptureService->sendRequest([
                'amount' => '5.00',
                //'trackData' => [
                //    'track1' => 'Track 1 Data',
                //    'track2' => 'Track 2 Data',
                //],
                'creditCard' => [
                    'cardNumber' => '4111111111111111',
                    'expirationDate' => '2017-01',
                    'cardCode' => '123',
                ],
                //'bankAccount' => [
                //    'accountNumber' => '100000000',
                //    'routingNumber' => '071902878',
                //    'nameOnAccount' => 'John Doe',
                //    'accountType' => 'checking',
                //],
                'profile' => [
                    'createProfile' => true,
                ],
                //'solution' => [
                //    'id' => '12345',
                //],
                //'authCode' => '123',
                'employeeId' => '12345',
                'order' => [
                    'invoiceNumber' => '12345',
                    'description' => 'Order Description',
                ],
                'lineItems' => [
                    [
                        'itemId' => '12345',
                        'name' => 'John Doe',
                        'description' => 'Test Description',
                        'quantity' => '1',
                        'unitPrice' => '5.00',
                        'taxable' => false,
                    ],
                    [
                        'itemId' => '12346',
                        'name' => 'John Doe 2',
                        'description' => 'Test Description',
                        'quantity' => '10',
                        'unitPrice' => '10.00',
                        'taxable' => true,
                    ],
                ],
                'tax' => [
                    'amount' => '5.00',
                    'name' => 'Tax Name',
                    'description' => 'Tax Description',
                ],
                'duty' => [
                    'amount' => '5.00',
                    'name' => 'Duty Name',
                    'description' => 'Duty Description',
                ],
                'shipping' => [
                    'amount' => '5.00',
                    'name' => 'Shipping Name',
                    'description' => 'Shipping Description',
                ],
                'taxExempt' => false,
                'poNumber' => '12345',
                'customer' => [
                    'type' => 'individual',
                    'id' => '12345',
                    'email' => 'test@test.com',
                ],
                'billTo' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'company' => 'Soliant Consulting',
                    'address' => '14 N Peoria St.',
                    'city' => 'Chicago',
                    'state' => 'IL',
                    'zip' => '60607',
                    'country' => 'US',
                    'phoneNumber' => '5555555555',
                    'faxNumber' => '5555555555',
                    'email' => 'test@test.com',
                ],
                'shipTo' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'company' => 'Soliant Consulting',
                    'address' => '14 N Peoria St.',
                    'city' => 'Chicago',
                    'state' => 'IL',
                    'zip' => '60607',
                    'country' => 'US',
                    'customerIP' => '0.0.0.0'
                ],
                'userFields' => [
                    [
                        'name' => 'test field',
                        'value' => 'test value',
                    ],
                ],
            ]);

            if ($response->isSuccess()) {
                // do some post processing here
                $data = $response->getData();
            }
        }

        return new ViewModel([
            'messages' => $response === null ? $response : $response->getMessages(),
            'data' => $data
        ]);
    }
}
