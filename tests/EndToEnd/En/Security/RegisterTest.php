<?php
namespace App\Tests\EndToEnd\En\Security;


use App\Config\EnTextConfig;
use Facebook\WebDriver\WebDriverBy;
use App\Tests\EndToEnd\EndToEndTest;
use App\Tests\Utils\UserFixturesTrait;
use App\DataFixtures\Tests\UserTestFixtures;

class RegisterTest extends EndToEndTest
{

    use UserFixturesTrait;


    public function testInvalidDifferentsPasswords()
    {
        $this->tryRegister($this->getFaker()->email(), 'password', 'otherpassword', true);
        $this->assertSelectorNotExists('.alert.alert-success');
        $this->assertSelectorExists('.form-error');
    }
    public function testInvalidBlankPassword()
    {
        $this->tryRegister($this->getFaker()->email(), '', '', true);
        $this->assertSelectorNotExists('.alert.alert-success');
        $this->assertSelectorExists('.form-error');
    }
    public function testInvalidBlankEmail()
    {
        $this->tryRegister('', 'password', 'password', true);
        $this->assertSelectorNotExists('.alert.alert-success');
        $this->assertSelectorExists('.form-error');
    }
    public function testInvalidNotAgreeTerms()
    {
        $this->tryRegister($this->getFaker()->email(), 'password', 'password', false);
        $this->assertSelectorNotExists('.alert.alert-success');
        $this->assertSelectorExists('.form-error');
    }
    public function testInvalidExistingEmail()
    {
        $existingEmail = $this->findUser([])->getEmail();
        
        $this->tryRegister($existingEmail, 'motdepasse', 'motdepasse', true);
        $this->assertSelectorNotExists('.alert.alert-success');
        $this->client->waitFor('.form-error', 5);
        $this->assertSelectorTextContains('.form-error', 'existing account');
    }
    public function testValidRegistration()
    {
        $this->tryRegister($this->getFaker()->email(), 'motdepasse', 'motdepasse', true);
        $this->client->waitFor('.alert.alert-success', 5);
        $this->assertSelectorTextContains('.alert.alert-success', EnTextConfig::ALERT_REGISTER_SUCCESS);
        $this->assertSelectorNotExists('.form-error');
    }
    public function testOnValidRegistrationUserPersisted()
    {
        $email = $this->getFaker()->email();
        $password = 'motdepassedetest';

        $this->tryRegister($email, $password, $password, true);
        $this->client->waitFor('.alert.alert-success', 5);
        $this->assertSelectorTextContains('.alert.alert-success', EnTextConfig::ALERT_REGISTER_SUCCESS);

        $this->tryLogin($email, $password);
        $this->assertSelectorTextContains('.form-main-error', EnTextConfig::ERROR_NOT_CONFIRMED_USER);
        $this->assertSelectorTextNotContains('.form-main-error', EnTextConfig::ERROR_INVALID_CREDENTIALS);
    }
    
    private function tryRegister(string $email, string $plainPassword, string $passwordConfirm, bool $agreeTerms = false)
    {
        // on logout d'abord car si on est déjà loggé la page security_register n'est pas accessible
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));

        $this->loadFixtures([UserTestFixtures::class]);
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_security_register'));
        $form = $crawler->selectButton('Create an account')->form([
            'email' => $email,
            'plainPassword' => $plainPassword,
            'passwordConfirm' => $passwordConfirm
        ]);
        if($agreeTerms)
        {
            $this->client->getWebDriver()->findElement(WebDriverBy::cssSelector('.custom-checkbox'))->click();   // custom-checkbox est lié à la checkbox agreeTerms en js
        }
        $this->client->submit($form);
    }

    private function tryLogin(string $email, string $password)
    {
        // on logout d'abord car si on est déjà loggé la page security_login n'est pas accessible
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));

        $this->loadFixtures([UserTestFixtures::class]);
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_security_login'));
        $form = $crawler->selectButton('Log in')->form([
            'email' => $email,
            'password' => $password,
        ]);
        $this->client->submit($form);
    }
} 
