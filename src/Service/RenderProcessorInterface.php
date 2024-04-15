<?php

declare(strict_types=1);

namespace App\Service;

interface RenderProcessorInterface
{
  public function setFilename(string $filename): void;
  public function getRenderQuotation(): int;
  public function processRenderRequest(): void;
}
