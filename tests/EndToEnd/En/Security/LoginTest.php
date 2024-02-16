<?php
namespace App\Tests\EndToEnd\En\Security;

use App\DataFixtures\Tests\UserTestFixtures;
use App\Tests\EndToEnd\EndToEndTest;
use App\Tests\Utils\UserFixturesTrait;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\BrowserKit\Cookie;

class LoginTest extends EndToEndTest
{

    use UserFixturesTrait;

    public function testPasswordForgottenLink()
    {
        //on logout avant au cas ou car si on est loggé on ne peut pas accéder à cette page
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));
        
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_security_login'));
        $crawler->findElement(WebDriverBy::linkText('Forgot your password ?'))->click();
        $this->client->waitForElementToContain('label', 'Enter your email address', 5);
        $this->assertSelectorTextContains('label', 'Enter your email address');
    }

    public function testRegisterLink()
    {
        //on logout avant au cas ou car si on est loggé on ne peut pas accéder à cette page
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));

        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_security_login'));
        $crawler->findElement(WebDriverBy::linkText('Not registered ? Click here to create an account'))->click();
        $this->client->waitForElementToContain('.form-button', "CREATE AN ACCOUNT", 5);
        $this->assertSelectorTextContains('.form-button', "CREATE AN ACCOUNT");
    }

    public function testRememberMe()
    {
        $this->loginAndDestroySession(true);

        /** on essaie de se rendre sur une page qui nécessite d'être authentifié */
        $this->client->request('GET', $this->urlGenerator->generate('en_security_changePassword'));
        /** on doit pouvoir accéder */
        $this->client->waitForElementToContain('label', 'Old password', 5);
        $this->assertSelectorTextContains('label', 'Old password');
    }
    public function testDontRememberMe()
    {
        $this->loginAndDestroySession(false);

        /** on essaie de se rendre sur une page qui nécessite d'être authentifié */
        $this->client->request('GET', $this->urlGenerator->generate('en_security_changePassword'));
        /** on doit être redirigé vers login */
        $this->client->waitForElementToContain('.form-button', 'LOG IN', 5);
        $this->assertSelectorTextContains('.form-button', 'LOG IN');
    }

    private function loginAndDestroySession(bool $clickRememberMe)
    {
        $this->loadFixtures([UserTestFixtures::class]);
        $user = $this->findUserByEmail('confirmed_user@gmail.com');

        // on logout d'abord car si on est déjà loggé la page security_login n'est pas accessible
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));

        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_security_login'));
        $form = $crawler->selectButton('Log in')->form([
            'email' => $user->getEmail(),
            'password' => 'password'
        ]);
        if($clickRememberMe)
        {
            $crawler->findElement(WebDriverBy::cssSelector('.custom-checkbox'))->click();  // relié par js a _remember_me checkbox
        }
        $this->client->submit($form);
        $this->client->waitFor('.alert.alert-success', 3);

        /**on supprime le cookie de la session */
        $this->client->getCookieJar()->set(new Cookie('PHPSESSID', '', time() - 1));
    }
}