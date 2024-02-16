<?php
namespace App\Tests\Functional\Controller\En\Security;


trait LoginTrait 
{
    public function tryLogin(string $email, string $password)
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_security_login'));
        $form = $crawler->selectButton('Log in')->form([
            'email' => $email,
            'password' => $password
        ]);
        $this->client->submit($form);
    }

    public function assertLoginFail()
    {
        $this->assertResponseRedirects($this->urlGenerator->generate('en_security_login'));
        $this->client->followRedirect();
        $this->assertSelectorExists('.form-main-error');
    }
}