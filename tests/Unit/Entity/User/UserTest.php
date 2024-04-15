<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\User;
use App\Tests\Personas\JohnDoe;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
  public function testCanCreate(): void
  {
    $newUser = new User(
      JohnDoe::$fullName,
      JohnDoe::$email,
      User::INITIAL_CREDITS_AMOUNT
    );

    $this->assertJsonStringEqualsJsonString(json_encode([
      'id' => '',
      'fullName' => JohnDoe::$fullName,
      'email' => JohnDoe::$email,
    ]), json_encode($newUser->toArray()));
  }

  public function testFullNameTooShortOnUserCreation()
  {
    $this->expectException(InvalidArgumentException::class);

    $newUser = new User(
      '1',
      JohnDoe::$email,
      User::INITIAL_CREDITS_AMOUNT
    );
  }

  public function testEmailIsInvalid()
  {
    $this->expectException(InvalidArgumentException::class);

    $newUser = new User(
      JohnDoe::$fullName,
      'not_an_email',
      User::INITIAL_CREDITS_AMOUNT
    );
  }

  public function testMinimumAmountOfCreditsOnUserCreation()
  {
    $this->expectException(InvalidArgumentException::class);
    $newUser = new User(
      JohnDoe::$fullName,
      JohnDoe::$email,
      0
    );
  }

  public function testCanModifyFullName()
  {
    $user = new User(
      JohnDoe::$fullName . ' with a typo',
      JohnDoe::$email,
      User::INITIAL_CREDITS_AMOUNT
    );

    $user->updateFullName(JohnDoe::$fullName);

    $this->assertJsonStringEqualsJsonString(json_encode([
      'id' => '',
      'fullName' => JohnDoe::$fullName,
      'email' => JohnDoe::$email,
    ]), json_encode($user->toArray()));
  }

  public function testFullNameTooShortOnUserModification()
  {
    $this->expectException(InvalidArgumentException::class);

    $newUser = new User(
      JohnDoe::$fullName . ' with a typo',
      JohnDoe::$email,
      User::INITIAL_CREDITS_AMOUNT
    );

    $newUser->updateFullName('.');
  }
}
