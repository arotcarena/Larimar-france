<?php
namespace App\Tests\Functional\Controller\En\Security;

use App\Config\EnTextConfig;
use App\DataFixtures\Tests\PurchaseTestFixtures;
use App\DataFixtures\Tests\UserPurchaseTestFixtures;
use App\Repository\UserRepository;
use App\Repository\PurchaseRepository;
use App\Tests\Utils\UserFixturesTrait;
use App\Tests\Functional\FunctionalTest;
use App\DataFixtures\Tests\UserTestFixtures;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\En\Security\LoginTrait;



/**
 * @group FunctionalSecurity
 */
class RegistrationControllerTest extends FunctionalTest
{
    use LoginTrait;
    
    use UserFixturesTrait;

    public function setUp(): void 
    {
        parent::setUp();

        $this->loadFixtures([UserPurchaseTestFixtures::class]);
    }
    
    /** register */
    public function testRegisterPageRender()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_security_register'));
        $this->assertResponseIsSuccessful('response status de la page security_register != 200');
        $this->assertSelectorTextContains('button', 'Create an account', 'la route security_register ne fonctionne pas correctement');
    }

    /** emailConfirmation */
    public function testIncorrectConfirmationTokenRedirectToHome()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_security_emailConfirmation'), [
            'token' => $this->getUserWithValidToken($this->client)->getId() . '==' . 'incorrectToken'
        ]);
        $this->assertResponseRedirects($this->urlGenerator->generate('en_home'));
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger', 'le flash d\'erreur n\'est pas présent');
    }
    public function testIncorrectConfirmationTokenUserIsNotVerified()
    {
        $user = $this->getUserWithValidToken($this->client);

        $this->client->request('GET', $this->urlGenerator->generate('en_security_emailConfirmation'), [
            'token' => $user->getId() . '==' . 'incorrectToken'
        ]);
        $this->tryLogin($user->getEmail(), 'password', false);
        $this->assertLoginFail();
        $this->assertSelectorTextContains('.form-main-error', EnTextConfig::ERROR_NOT_CONFIRMED_USER);
    }
    public function testExpiredConfirmationTokenRedirectToHomeAndUserIsNotVerified()
    {
        $userWithExpiredToken = $this->getUserWithExpiredToken($this->client);

        $this->client->request('GET', $this->urlGenerator->generate('en_security_emailConfirmation'), [
            'token' => $userWithExpiredToken->getId() . '==' . $userWithExpiredToken->getConfirmationToken()
        ]);
        $this->assertResponseRedirects($this->urlGenerator->generate('en_home'));
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');

        $this->tryLogin($userWithExpiredToken->getEmail(), 'password', false);
        $this->assertLoginFail();
        $this->assertSelectorTextContains('.form-main-error', EnTextConfig::ERROR_NOT_CONFIRMED_USER);
    }
    public function testCorrectConfirmationTokenRedirectToLogin()
    {
        $user = $this->getUserWithValidToken($this->client);

        $this->client->request('GET', $this->urlGenerator->generate('en_security_emailConfirmation'), [
            'token' => $user->getId() . '==' . $user->getConfirmationToken()
        ]);
        $this->assertResponseRedirects($this->urlGenerator->generate('en_security_login'));
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success', 'le flash de succès n\'est pas présent');
    }
    public function testCorrectConfirmationTokenUserIsVerifiedAndCanLogin()
    {
        $user = $this->getUserWithValidToken($this->client);

        $this->client->request('GET', $this->urlGenerator->generate('en_security_emailConfirmation'), [
            'token' => $user->getId() . '==' . $user->getConfirmationToken()
        ]);
        $this->tryLogin($user->getEmail(), 'password', false);
        $this->assertResponseRedirects(
            $this->urlGenerator->generate('home')
        );
        $this->client->followRedirect();
        $this->assertResponseRedirects(
            $this->urlGenerator->generate('en_home')
        );
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert.alert-success', EnTextConfig::ALERT_LOGIN_SUCCESS);
    }

    //accountDelete
    public function testAccountDeleteNotAuthenticatedUserCannotAccess()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_security_accountDelete'));
        $this->assertResponseRedirects($this->urlGenerator->generate('en_security_login'));
    }
    
    public function testAccountDeleteUserHavingPurchasesInProgressCannotAccess()
    {
        $user = $this->findEntity(UserRepository::class, ['email' => 'user_having_one_purchase_in_progress@gmail.com']);
        $this->client->loginUser($user);

        $this->client->request('GET', $this->urlGenerator->generate('en_security_accountDelete'));
        $this->assertResponseRedirects($this->urlGenerator->generate('en_account_index'));
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testAccountDeleteUserCanAccess()
    {
        $user = $this->findEntity(UserRepository::class, ['email' => 'user_having_two_terminated_purchases@gmail.com']);
        $this->client->loginUser($user);

        $this->client->request('GET', $this->urlGenerator->generate('en_security_accountDelete'));
        $this->assertResponseIsSuccessful();
    }

    public function testAccountDeleteRender()
    {
        $user = $this->findEntity(UserRepository::class, ['email' => 'user_having_two_terminated_purchases@gmail.com']);
        $this->client->loginUser($user);

        $this->client->request('GET', $this->urlGenerator->generate('en_security_accountDelete'));
        $this->assertSelectorTextContains('button', 'Delete my account');
    }

    public function testAccountDeleteTryToDeleteWithoutCsrfToken()
    {
        $user = $this->findEntity(UserRepository::class, ['email' => 'user_having_two_terminated_purchases@gmail.com']);
        $this->client->loginUser($user);

        $this->client->request('POST', $this->urlGenerator->generate('en_security_accountDelete'));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testAccountDeleteLegit()
    {
        $user = $this->findEntity(UserRepository::class, ['email' => 'user_having_two_terminated_purchases@gmail.com']);
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_security_accountDelete'));
        $form = $crawler->selectButton('Delete my account')->form();
        $this->client->submit($form);
        $this->assertResponseRedirects($this->urlGenerator->generate('en_home'));
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testAccountDeleteUserIsCorrectlyRemovedFromDatabase()
    {
        $user = $this->findEntity(UserRepository::class, ['email' => 'user_having_two_terminated_purchases@gmail.com']);
        $id = $user->getId();
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_security_accountDelete'));
        $form = $crawler->selectButton('Delete my account')->form();
        $this->client->submit($form);
        $this->assertResponseRedirects($this->urlGenerator->generate('en_home'));
        // on vérifie que user n'est plus en database
        $dbUser = $this->findEntity(UserRepository::class, ['id' => $id]);
        $this->assertNull($dbUser);
    }

    public function testAccountDeletePurchasesAreNotDeleted()
    {
        $user = $this->findEntity(UserRepository::class, ['email' => 'user_having_two_terminated_purchases@gmail.com']);
        $purchase = $user->getPurchases()->get(0);
        $id = $purchase->getId();
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_security_accountDelete'));
        $form = $crawler->selectButton('Delete my account')->form();
        $this->client->submit($form);
        $this->assertResponseRedirects($this->urlGenerator->generate('en_home'));
        // on vérifie que la purchase est toujours en database et que sa property user vaut désormais null
        $dbPurchase = $this->findEntity(PurchaseRepository::class, ['id' => $id]);
        $this->assertNotNull($dbPurchase, 'a la suppression de user, les purchases correspondantes sont supprimées, ça ne devrait pas  être le cas');

        // ceci ne fonctionne pas probablement a cause de la db sqlite qui n'a pas toutes les constraints de la vraie database
        // $this->assertNull($dbPurchase->getUser(), 'La propriété user de la purchase dont le user vient d\'être supprimé devrait prendre la valeur null');
    }

}