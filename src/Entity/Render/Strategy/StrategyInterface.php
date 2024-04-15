<?php
declare(strict_types=1);

namespace App\Entity\Render\Strategy;

interface StrategyInterface {
  public function getQUotation(): int;
  public function processRequest(string $filePath): void;
}
