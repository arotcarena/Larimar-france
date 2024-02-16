<?php
namespace App\Tests\Functional\Controller\En;

use App\Tests\Functional\FunctionalTest;
use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;

/**
 * @group FunctionalHome
 */
class HomeControllerTest extends FunctionalTest
{
    public function testHomePageRender()
    {
        $this->client->request('GET', $this->urlGenerator->generate('home'), [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en-EN,en;q=0.9,en-US;q=0.8,en;q=0.7']);
        $this->assertResponseRedirects(
            $this->urlGenerator->generate('en_home')
        );
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }
    public function testDatabaseQueriesCount()
    {
        $this->client->enableProfiler();

        $this->client->request('GET', $this->urlGenerator->generate('en_home'));
        /** @var DoctrineDataCollector */
        $dbCollector = $this->client->getProfile()->getCollector('db');
        $this->assertLessThanOrEqual(4, $dbCollector->getQueryCount());
    }
}