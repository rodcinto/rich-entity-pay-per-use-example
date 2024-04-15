<?php

declare(strict_types=1);

namespace App\Entity\Render\Strategy;

class RenderV1 implements StrategyInterface
{
  private const USAGE_COST = 100;

  private const NAME = '3D Render v1.87';
  private const DESCRIPTION = 'Transforms a package of images into 3D Render.';

  public function getQuotation(): int
  {
    return $this::USAGE_COST;
  }

  public function processRequest(string $filePath): void
  {
    // Internal logic for creating the 3D model.
  }
}

