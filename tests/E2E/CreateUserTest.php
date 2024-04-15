<?php

namespace App\Tests\E2E;

use App\Tests\Personas\JohnDoe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserTest extends WebTestCase
{
  const HTTP_METHOD = 'POST';
  const API_PATH = '/user';
  public function testCreateUser(): void
  {
    $response = static::createClient()->jsonRequest($this::HTTP_METHOD, $this::API_PATH, [
      'fullName' => JohnDoe::$fullName,
      'email' => JohnDoe::$email,
    ]);

    $this->assertResponseStatusCodeSame(201);
  }
}
