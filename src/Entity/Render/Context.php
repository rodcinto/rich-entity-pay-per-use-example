<?php

declare(strict_types=1);

namespace App\Entity\Render;

use App\Entity\Render\Strategy\StrategyInterface;

class Context implements RenderInterface
{
  private StrategyInterface $strategy;

  public function getQuotation(): int
  {
    return $this->strategy->getQuotation();
  }
  public function processRequest(string $filePath): void
  {
    $this->strategy->processRequest($filePath);
  }

  public function setStrategy(StrategyInterface $strategy): void
  {
    $this->strategy = $strategy;
  }
}
