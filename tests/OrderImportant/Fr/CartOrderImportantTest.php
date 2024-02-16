<?php
namespace App\Tests\OrderImportant\Fr;

use Facebook\WebDriver\WebDriverBy;
use App\Tests\EndToEnd\EndToEndTest;
use Facebook\WebDriver\WebDriverKeys;
use App\Tests\EndToEnd\Fr\Utils\CartTrait;
use App\Twig\Runtime\PriceFormaterExtensionRuntime;
use App\Tests\Functional\Controller\Fr\Security\LoginTrait;

/**
 * CES FIXTURES DOIVENT ETRE JOUEES DABS L ORDRE
 * @group FrOrderImportant
 */
class CartOrderImportantTest extends EndToEndTest
{
    use LoginTrait;

    use CartTrait;

    private PriceFormaterExtensionRuntime $priceFormater;

    public function setUp(): void 
    {
        parent::setUp();

        $this->priceFormater = static::getContainer()->get(PriceFormaterExtensionRuntime::class);
    }

    public function testAddProduct()
    {
        //on logout au cas ou avant de démarrer tous ces tests
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));


        //on ajoute un product
        $this->addProduct('produit'); //produit-sans-sous-catégorie stock=1 50€ 

        $this->client->waitForElementToContain('.cart-count-chip', '1', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '1');

        //on ajoute un product différent
        $this->addProduct('mon'); // mon objet stock=50 350€

        $this->client->waitForElementToContain('.cart-count-chip', '2', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '2');
    }
    public function testRemoveProduct()
    {
        //suppression du dernier product
        $this->openCartMenu();
        $this->client->findElement(WebDriverBy::cssSelector('.cart-line-wrapper:last-of-type .cart-line .cart-line-remover'))->click();
        $this->client->waitFor('.confirm-remove-button.yes', 5);
        $this->client->findElement(WebDriverBy::cssSelector('.confirm-remove-button.yes'))->click();

        //on vérifie qu'il n'y a plus que un product
        $this->client->waitForElementToContain('.cart-count-chip', '1', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '1');
    }
    public function testAddQuantity()
    {
        //on ajoute 1 product
        $this->addProduct('mon'); // mon objet stock=50 350€
        $this->client->waitForElementToContain('.cart-count-chip', '2', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '2');

        // PAS DE ADD QUANTITY DANS LARIMAR
        // //on ajoute 1 au dernier product ajouté
        // $this->openCartMenu();
        // $this->client->findElement(WebDriverBy::cssSelector('.cart-line:last-of-type .cart-line-plus'))->click();
        
        // //on vérifie qu'il ya bien 3 products au total
        // $this->client->waitForElementToContain('.cart-count-chip', '3', 5);
        // $this->assertSelectorTextContains('.cart-count-chip', '3');
    }
    // PAS DE LESS QUANTITY DANS LARIMAR
    // public function testLessQuantity()
    // {
    //     //on enlève 1 au dernier product
    //     $this->openCartMenu();
    //     $this->client->findElement(WebDriverBy::cssSelector('.cart-line:last-of-type .cart-line-minus'))->click();

    //     //on vérifie qu'il ya bien 2 products au total
    //     $this->client->waitForElementToContain('.cart-count-chip', '2', 5);
    //     $this->assertSelectorTextContains('.cart-count-chip', '2');
    // }
    public function testGetFullCartCorrectInfosAndOrder()
    {
        //on vérifie que le getFullCart renvoie les bonnes infos
        $this->openCartMenu();
        $this->assertSelectorTextContains('.cart-line-wrapper:first-of-type .cart-line-title', 'produit sans sous-catégorie');
        $this->assertSelectorTextContains('.cart-line-wrapper:last-of-type .cart-line-title', 'mon objet');
        $this->assertSelectorTextContains('.cart-total', '400,00 €');
        $this->assertSelectorTextContains('.cart-title', '(2)');
    }
    public function testGetLightCart()
    {
        //on vérifie que le getLightCart renvoie les bonnes infos
        $this->client->waitForElementToContain('.cart-count-chip', '2', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '2');
        $this->assertSelectorTextContains('.cart-price-chip', '400,00 €');
    }

    
    public function testCartMergeOnLoginWhenCartInDatabase()
    {
        // on se connecte
        // il y a 5 product dans le cart de ce user en database
        $this->tryLogin('confirmed_user@gmail.com', 'password');

        //on vérifie qu'il y a maintenant 7 products
        $this->client->waitForElementToContain('.cart-count-chip', '7', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '7');
    }
    
   
    public function testPersistCartWhenUserIsLogged()
    {
        $this->addProduct('produitsanscatégorie');
        $this->client->waitForElementToContain('.cart-count-chip', '8', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '8');
       
        //logout
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));
        $this->client->waitForElementToContain('.cart-count-chip', '', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '');

        //login
        $this->tryLogin('confirmed_user@gmail.com', 'password');
        $this->client->waitForElementToContain('.cart-count-chip', '8', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '8');
    }
    public function testCartMenuContainsPurchaseButton()
    {
        $this->openCartMenu();
        $this->client->waitFor('.cart-footer-link');
        $this->assertSelectorAttributeContains('.cart-footer-link', 'href', $this->urlGenerator->generate('fr_purchase_create'));
    }
    public function testPurchaseButtonRedirectToPurchasePage()
    {
        $this->openCartMenu();
        $this->client->waitFor('.cart-footer-link', 5);
        $this->client->findElement(WebDriverBy::cssSelector('.cart-footer-link'))->click();
        $this->assertSelectorTextContains('h1', 'PASSER COMMANDE');
    }
    public function testEmptyCorrectEmptyCart()
    {
        $this->client->request('GET', $this->urlGenerator->generate('tests_cartService_empty')); // controller de test qui appelle CartService.empty()
        //tests_cartService renvoie une Response vide donc il faut recharger une page
        $this->client->request('GET', $this->urlGenerator->generate('home'));
        $this->client->waitForElementToContain('.cart-count-chip', '', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '');

        //on logout et login pour vérifier aussi le cart de la db
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));
        $this->tryLogin('confirmed_user@gmail.com', 'password');

        $this->client->waitForElementToContain('.cart-count-chip', '', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '');
    }
    public function testEmptyCartDoesntContainsPurchaseButton()
    {
        $this->client->request('GET', $this->urlGenerator->generate('home'));
        $this->client->waitFor('.cart-opener', 5);
        $this->client->findElement(WebDriverBy::cssSelector('.cart-opener'))->click();
        
        $this->client->waitForElementToContain('.cart-sub-header', 'Le panier est vide', 5);
        $this->assertSelectorTextContains('.cart-sub-header', 'Le panier est vide');
        $this->assertSelectorNotExists('.cart-footer-link');
    }
    public function testAdminUpdateStockZero()
    {
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));
        $this->tryLogin('confirmed_user@gmail.com', 'password');
        
        $this->addProduct('mon');  // mon objet stock = 50
        $this->addProduct('public'); // un product différent
        $this->client->waitForElementToContain('.cart-count-chip', '2', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '2');

        //on logout et login en tant qu'admin pour paser le stock de "my object" à 2
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));
        $this->tryLogin('admin@gmail.com', 'password');
        //on recherche le product sur la page index
        $this->client->request('GET', $this->urlGenerator->generate('admin_product_index', ['q' => 'mon objet']));  // = my object  dans admin c'est en français
        //on clique sur modifier le product
        $this->client->findElement(WebDriverBy::cssSelector('tbody tr:first-child td:last-child .admin-table-button:nth-child(2)'))->click();
        //update form
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->waitForVisibility('input#product_stock', 5);
        $this->client->findElement(WebDriverBy::cssSelector('input#product_stock'))->click();
        $this->client->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);  //2 fois car stock à 50
        $this->client->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);
        $this->client->getKeyboard()->pressKey('0');
        $this->client->findElement(WebDriverBy::cssSelector('button[type=submit]'))->click();

        $this->assertSelectorExists('.alert.alert-success');

        //on logout et login en tant qu'user
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));
        $this->tryLogin('confirmed_user@gmail.com', 'password');

        $this->client->waitForElementToContain('.cart-count-chip', '1', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '1');
    }
    public function testAdminRemoveProduct()
    {
        //on remet my product
        $this->addProduct('produit');
        $this->client->waitForElementToContain('.cart-count-chip', '2', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '2');
        //on logout et login en tant qu'admin pour supprimer public
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));
        $this->tryLogin('admin@gmail.com', 'password');
        //on recherche le product sur la page index
        $this->client->request('GET', $this->urlGenerator->generate('admin_product_index', ['q' => 'public']));
        //on clique sur supprimer le product et on confirme
        $this->client->executeScript('confirm = () => true');
        $this->client->findElement(WebDriverBy::cssSelector('tbody tr:first-child td:last-child form .admin-table-button'))->click();
        $this->assertSelectorExists('.alert.alert-success');
        //on logout et login comme user
        //on doit vérifier qu'il n'y a plus qu'une seule cartLine
        $this->client->request('GET', $this->urlGenerator->generate('security_logout'));
        $this->tryLogin('confirmed_user@gmail.com', 'password');

        $this->client->waitForElementToContain('.cart-count-chip', '1', 5);
        $this->assertSelectorTextContains('.cart-count-chip', '1');
        $this->openCartMenu();
        $this->assertSelectorExists('.cart-line');
        $this->assertSelectorNotExists('.cart-line-wrapper:nth-child(2)');
    }
}