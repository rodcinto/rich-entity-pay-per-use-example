<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\Credits;
use PHPUnit\Framework\TestCase;

class CreditsTest extends TestCase
{
  public function testCanInstantiate(): void
  {
    $credits = new Credits();
    $this->assertInstanceOf(Credits::class, $credits);
  }

  public function testShouldDeductAmount(): void
  {
    $credits = new Credits(10);

    $this->assertFalse($credits->canDeduct(100));
    $this->assertFalse($credits->canDeduct(-100));
    $this->assertTrue($credits->canDeduct(1));
  }

  public function testCanDeductAmount(): void
  {
    $credits = new Credits(10);

    $credits->deduct(10);

    $this->assertFalse($credits->canDeduct(1));
  }

  public function canAddAmount(): void
  {
    $credits = new Credits(10);
    $credits->add(10);
    $this->assertTrue($credits->canDeduct(20));
    $this->assertFalse($credits->canDeduct(21));
  }

  public function testDeductHigherThanMinBecomesMin(): void
  {
    $credits = new Credits(1);

    $credits->deduct(10);
    $credits->add(1);

    $this->assertTrue($credits->canDeduct(1));
    $this->assertFalse($credits->canDeduct(2));
  }
}
