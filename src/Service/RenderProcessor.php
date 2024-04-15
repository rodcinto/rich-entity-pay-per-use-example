<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Render\Context;
use App\Entity\Render\RenderInterface;
use App\Entity\Render\Strategy\RenderV2;

class RenderProcessor implements RenderProcessorInterface
{
  private string $filename;

  private RenderInterface $render;

  public function __construct() {
    $this->render = new Context();
    // Logic to decide what Render strategy...
    $this->render->setStrategy(new RenderV2());
    // This service could be as leveraged as you like,
    // For example, Symfony Service Tags, Factories, Setters, or whatever.
    // For now, it hides the awareness of the Render Entity from User Entity.
  }

  public function setFilename(string $filename): void
  {
    $this->filename = $filename;
  }

  public function getRenderQuotation(): int
  {
    return $this->render->getQuotation();
  }

  public function processRenderRequest(): void
  {
    try {
      $this->render->processRequest($this->filename);
    } catch (\Throwable $th) {
      //throw $th;
      // Treat or log all the exceptions coming from the Render,
      // and decide what to expose.
    }
  }
}

