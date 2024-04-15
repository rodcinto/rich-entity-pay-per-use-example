<?php

declare(strict_types=1);

namespace App\Entity\Render\Strategy;

class RenderV2 implements StrategyInterface
{
  private const USAGE_COST = 100;

  private const NAME = '3D Render v2.33';
  private const DESCRIPTION = 'Transforms a package of images into 3D Render.';
  private const ENDPOINT = 'http://external-endpoint';

  public function getQuotation(): int
  {
    return $this::USAGE_COST;
  }

  public function processRequest(string $filePath): void
  {
    // Internal logic to process the request or even call other proxies for the endpoint.
  }
}

