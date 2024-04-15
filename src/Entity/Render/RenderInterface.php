<?php

declare(strict_types=1);

namespace App\Entity\Render;

use App\Entity\Render\Strategy\StrategyInterface;

interface RenderInterface
{
  public function setStrategy(StrategyInterface $strategy);
  public function getQuotation(): int;
  public function processRequest(string $filePath): void;
}
