<?php
namespace App\Tests\UnitAndIntegration\Twig\Runtime;

use App\Twig\Runtime\ExcerptMakerExtensionRuntime;
use PHPUnit\Framework\TestCase;

class ExcerptMakerExtensionRuntimeTest extends TestCase
{
    public function testWithNoParamAndTextLongerThan20Words()
    {
        $result = (new ExcerptMakerExtensionRuntime)->excerpt($this->get25Words());
        $this->assertCount(
            20,
            explode(' ', $result)
        );
        $this->assertStringEndsWith('...', $result);
    }
    public function testWithNoParamAndTextShorterThan20Words()
    {
        $result = (new ExcerptMakerExtensionRuntime)->excerpt($this->get15Words());
        $this->assertEquals($this->get15Words(), $result);
    }
    public function testWithParamWordsAndTextLongerThanParamWords()
    {
        $result = (new ExcerptMakerExtensionRuntime)->excerpt($this->get50Words(), 45);
        $this->assertCount(
            45,
            explode(' ', $result)
        );
        $this->assertStringEndsWith('...', $result);
    }
    public function testWithParamWordsAndTextShorterThanParamWords()
    {
        $result = (new ExcerptMakerExtensionRuntime)->excerpt($this->get50Words(), 60);
        $this->assertEquals($this->get50Words(), $result);
    }
    public function testInCaseOfTruncateContentIsCorrectlySelectedAtTheBeginningOfTheString()
    {
        $result = (new ExcerptMakerExtensionRuntime)->excerpt($this->get25Words());
        $this->assertEquals('Cette phrase contient vingt-cinq mots, et pas un de plus ni un de moins, car il faut un nombre exact...', $result);
    }

    private function get15Words() {
        return 'Cette phrase contient quinze mots, et pas un de plus ni un de moins, voila.';
    }
    private function get25Words() {
        return 'Cette phrase contient vingt-cinq mots, et pas un de plus ni un de moins, car il faut un nombre exact de vingt-cinq c\'est très important.';
    }
    private function get50Words() {
        return 'Cette phrase contient cinquante mots, et pas un de plus ni un de moins, car il faut un nombre exact de vingt-cinq c\'est très important. Cette phrase contient cinquante mots, et pas un de plus ni un de moins, car il faut un nombre exact de vingt-cinq c\'est très important.';
    }
}