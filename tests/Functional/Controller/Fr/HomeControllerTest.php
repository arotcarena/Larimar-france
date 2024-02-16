<?php
namespace App\Tests\Functional\Controller\Fr;

use App\Tests\Functional\FrFunctionalTest;
use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;

/**
 * @group FunctionalHome
 */
class HomeControllerTest extends FrFunctionalTest
{
    public function testHomePageRender()
    {
        $this->client->request('GET', $this->urlGenerator->generate('home'), [], [], ['HTTP_ACCEPT_LANGUAGE' => 'fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7']);
        $this->assertResponseIsSuccessful('response status de la page home != 200');
    }
    public function testDatabaseQueriesCount()
    {
        $this->client->enableProfiler();

        $this->client->request('GET', $this->urlGenerator->generate('home'));
        /** @var DoctrineDataCollector */
        $dbCollector = $this->client->getProfile()->getCollector('db');
        $this->assertLessThanOrEqual(4, $dbCollector->getQueryCount());
    }
}