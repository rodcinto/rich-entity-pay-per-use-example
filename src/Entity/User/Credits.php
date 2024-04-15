<?php

declare(strict_types=1);

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Credits
{
  private const MIN_ACCEPTABLE_AMOUNT = 0;

  #[ORM\Column(options: ['default' => 0])]
  private ?int $amount = null;

  public function __construct($initialAmount = 0)
  {
    $this->amount = $initialAmount;
  }

  public function canDeduct(int $amount): bool
  {
    return $this->amount - $this->sanitize($amount) >= $this::MIN_ACCEPTABLE_AMOUNT;
  }

  public function deduct(int $amount): void
  {
    if ($this->canDeduct($amount)) {
      $this->amount -= $this->sanitize($amount);
      return;
    }
    $this->amount = $this::MIN_ACCEPTABLE_AMOUNT;
  }

  public function add(int $amount): void
  {
    $this->amount += $this->sanitize($amount);
  }

  private function sanitize(int $value): int
  {
    return abs($value);
  }
}
