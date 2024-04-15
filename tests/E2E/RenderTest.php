<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RenderTest extends WebTestCase
{
  const HTTP_METHOD = 'POST';
  const API_PATH = '/user/1/render';
  public function testRenderRequest(): void
  {
    $response = static::createClient()->jsonRequest($this::HTTP_METHOD, $this::API_PATH, [
      'zipfile' => 'a_zip_file.zip'
    ]);

    $this->assertResponseStatusCodeSame(200);
  }
}
