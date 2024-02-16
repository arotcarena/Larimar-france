<?php
namespace App\Tests\Functional\Controller\Fr\Api\Contact;

use Error;
use App\Repository\ProductRepository;
use App\Tests\Functional\FrFunctionalTest;
use App\Email\Admin\AdminNotificationEmail;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\DataFixtures\Tests\ProductTestFixtures;
use App\Controller\En\Api\Contact\ApiContactController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiContactControllerTest extends FrFunctionalTest
{
    public function setUp(): void 
    {
        parent::setUp();

        $this->loadFixtures([ProductTestFixtures::class]);
    }

    public function testFingerSizeWithNoProductPublicRefParam()
    {
        $this->client->request('POST', $this->urlGenerator->generate('fr_api_contact_fingerSize'), [], [], [], json_encode([
            'fingerSize' => 10,
            'email' => 'validemail@gmail.com'
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testFingerSizeWithInvalidProductPublicRef()
    {
        $this->client->request('POST', $this->urlGenerator->generate('fr_api_contact_fingerSize'), [], [], [], json_encode([
            'fingerSize' => 10,
            'email' => 'validemail@gmail.com',
            'publicRef' => 'inexistant_public_ref_fjdksql'
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testFingerSizeWithInvalidFormHavingBlankFingerSize()
    {
        $this->client->request('POST', $this->urlGenerator->generate('fr_api_contact_fingerSize'), [], [], [], json_encode([
            'fingerSize' => '',
            'email' => 'validemail@gmail.com',
            'publicRef' => 'inexistant_public_ref_fjdksql'
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testFingerSizeWithCorrectParams()
    {
        $product = $this->findEntity(ProductRepository::class);
        $this->client->request('POST', $this->urlGenerator->generate('fr_api_contact_fingerSize'), [], [], [], json_encode([
            'fingerSize' => 10,
            'email' => 'validemail@gmail.com',
            'publicRef' => $product->getPublicRef()
        ]));
        $this->assertResponseIsSuccessful();
    }
    public function testFingerSizeWithCorrectParamsSendEmail()
    {
        /** @var MockObject|AdminNotificationEmail */
        $adminNotificationEmail = $this->createMock(AdminNotificationEmail::class);

        /** @var ProductRepository */
        $productRepository = $this->client->getContainer()->get(ProductRepository::class);
        
        /** @var ValidatorInterface */
        $validator = $this->client->getContainer()->get(ValidatorInterface::class);

        $controller = new ApiContactController($adminNotificationEmail, $productRepository, $validator);

        $adminNotificationEmail->expects($this->once())
                                ->method('send')
                                ;
        
        $product = $this->findEntity(ProductRepository::class);
        $request = new Request([], [], [], [], [], [], json_encode([
            'fingerSize' => 10,
            'email' => 'validemail@gmail.com',
            'publicRef' => $product->getPublicRef()
        ]));
        $controller->fingerSize($request);
    }
    public function testFingerSizeWithIncorrectParamsDontSendEmail()
    {
        /** @var MockObject|AdminNotificationEmail */
        $adminNotificationEmail = $this->createMock(AdminNotificationEmail::class);

        /** @var ProductRepository */
        $productRepository = $this->client->getContainer()->get(ProductRepository::class);
        
        /** @var ValidatorInterface */
        $validator = $this->client->getContainer()->get(ValidatorInterface::class);

        $controller = new ApiContactController($adminNotificationEmail, $productRepository, $validator);

        
        $adminNotificationEmail->expects($this->never())
                                ->method('send')
                                ;

        $request = new Request([], [], [], [], [], [], json_encode([
            'fingerSize' => 10,
            'email' => 'validemail@gmail.com',
            'publicRef' => 'inexistant_public_ref_jfdksjfk'
        ]));
        $this->expectException(Error::class);
        $controller->fingerSize($request);
                                
                                
        $adminNotificationEmail->expects($this->never())
                                ->method('send')
                                ;
                                
        $product = $this->findEntity(ProductRepository::class);
        $request = new Request([], [], [], [], [], [], json_encode([
            'fingerSize' => '',
            'email' => 'validemail@gmail.com',
            'publicRef' => $product->getPublicRef()
        ]));
        $controller->fingerSize($request);
    }
}