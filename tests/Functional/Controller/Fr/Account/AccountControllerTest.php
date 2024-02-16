<?php
namespace App\Tests\Functional\Controller\Admin;

use App\Tests\Functional\LoginUserTrait;
use App\Tests\Functional\FrFunctionalTest;
use App\DataFixtures\Tests\UserTestFixtures;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group FunctionalAccount
 */
class AccountControllerTest extends FrFunctionalTest
{
    use LoginUserTrait;

    public function setUp(): void 
    {
        parent::setUp();

        $this->loadFixtures([UserTestFixtures::class]);
    }


    public function testNotLoggedUserIsRedirectedToLogin()
    {
        $this->client->request('GET', $this->urlGenerator->generate('fr_account_index'));
        $this->assertResponseRedirects($this->urlGenerator->generate('fr_security_login'));
    }
    public function testUserCanAccess()
    {
        $this->loginUser();
        $this->client->request('GET', $this->urlGenerator->generate('fr_account_index'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mon compte');
    }
}