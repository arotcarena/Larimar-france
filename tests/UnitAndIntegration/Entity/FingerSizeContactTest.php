<?php
namespace App\Tests\UnitAndIntegration\Entity;

use App\Form\DataModel\FingerSizeContact;
use App\Tests\UnitAndIntegration\Entity\EntityTest;

/**
 * @group Entity
 */
class FingerSizeContactTest extends EntityTest
{
    public function testInvalidBlankEmail()
    {
        $this->assertHasErrors(1, $this->createValidFingerSizeContact()->setEmail(''));
    }

    public function testInvalidTooLongEmail()
    {
        $this->assertHasErrors(
            2,
            $this->createValidFingerSizeContact()->setEmail($this->moreThan200Caracters)
        );
    }

    public function testInvalidZeroFingerSize()
    {
        $this->assertHasErrors(
            1,
            $this->createValidFingerSizeContact()->setFingerSize(0)
        );
    }

    public function testInvalidNegativeFingerSize()
    {
        $this->assertHasErrors(
            1,
            $this->createValidFingerSizeContact()->setFingerSize(-4)
        );
    }

    public function testInvalidNullFingerSize()
    {
        $this->assertHasErrors(
            1,
            (new FingerSizeContact)->setEmail('emailvalide@gmail.com')
        );
    }

    private function createValidFingerSizeContact(): FingerSizeContact
    {
        return (new FingerSizeContact)
                ->setEmail('emailvalide@gmail.com')
                ->setFingerSize(32)
                ;
    }
}