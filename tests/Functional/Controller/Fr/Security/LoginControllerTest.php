<?php
namespace App\Tests\Functional\Controller\Fr\Security;


use App\Config\TextConfig;
use App\Tests\Utils\UserFixturesTrait;
use App\DataFixtures\Tests\UserTestFixtures;
use App\Tests\Functional\Controller\Fr\Security\LoginTrait;
use App\Tests\Functional\FrFunctionalTest;

/**
 * @group FunctionalSecurity
 */
class LoginControllerTest extends FrFunctionalTest
{
    use LoginTrait;
    use UserFixturesTrait;

    public function testLoginPageRender()
    {
        $this->client->request('GET', $this->urlGenerator->generate('fr_security_login'));
        $this->assertResponseIsSuccessful('response status de la page login != 200');
        $this->assertSelectorTextContains('button', 'Se connecter');
    }
    public function testLoginWithBadEmailButBadPassword()
    {
        $this->loadFixtures([UserTestFixtures::class], $this->client);
        $this->tryLogin('bademail@gmail.com', 'badpassword');
        $this->assertLoginFail();
        $this->assertSelectorTextContains('.form-main-error', TextConfig::ERROR_INVALID_CREDENTIALS);
    }
    public function testLoginWithCorrectEmailButBadPassword()
    {
        $this->loadFixtures([UserTestFixtures::class], $this->client);
        $this->tryLogin('confirmed_user@gmail.com', 'badpassword');
        $this->assertLoginFail();
        $this->assertSelectorTextContains('.form-main-error', TextConfig::ERROR_INVALID_CREDENTIALS);
    }
    public function testLoginWithCorrectCredentialsButRestrictedUser()
    {
        $this->loadFixtures([UserTestFixtures::class], $this->client);
        $this->tryLogin('restricted_user@gmail.com', 'password');
        $this->assertLoginFail();
        $this->assertSelectorTextContains('.form-main-error', TextConfig::ERROR_RESTRICTED_USER);
    }
    public function testLoginWithCorrectCredentialsButNotConfirmedUser()
    {
        $this->loadFixtures([UserTestFixtures::class], $this->client);
        $this->tryLogin('user@gmail.com', 'password');
        $this->assertLoginFail();
        $this->assertSelectorTextContains('.form-main-error', TextConfig::ERROR_NOT_CONFIRMED_USER); 
    }
    public function testLoginWithCorrectCredentialsAndConfirmedUser()
    {
        $this->loadFixtures([UserTestFixtures::class], $this->client);
        $this->tryLogin('confirmed_user@gmail.com', 'password');
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert.alert-success', TextConfig::ALERT_LOGIN_SUCCESS);
        $this->assertSelectorNotExists('.form-main-error', TextConfig::ERROR_INVALID_CREDENTIALS);
        $this->assertSelectorNotExists('.form-main-error', TextConfig::ERROR_NOT_CONFIRMED_USER); 
        $this->assertSelectorNotExists('.form-main-error', TextConfig::ERROR_RESTRICTED_USER);
    }
    

    public function testLogout()
    {
        $this->loadFixtures([UserTestFixtures::class], $this->client);
        
        $user = $this->findUserByEmail('confirmed_user@gmail.com', $this->client);
        $this->client->loginUser($user);

        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert.alert-success', TextConfig::ALERT_LOGOUT_SUCCESS);
    }
    
}