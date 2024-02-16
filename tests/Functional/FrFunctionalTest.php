<?php
namespace App\Tests\Functional;

use App\Tests\Utils\FixturesTrait;
use App\Tests\Functional\FunctionalTest;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class FrFunctionalTest extends FunctionalTest 
{
    public function setUp(): void
    {
        parent::setUp();

        $this->client->setServerParameter('HTTP_ACCEPT_LANGUAGE', 'fr-FR');
    }
}