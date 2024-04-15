<?php

namespace App\Entity\User;

use App\Entity\Render\Render;
use App\Repository\UserRepository;
use App\Service\RenderProcessorInterface;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
  public const INITIAL_CREDITS_AMOUNT = 100;
  private const MIN_FULLNAME_LENGTH = 3;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 200, nullable: true)]
  private ?string $fullName = null;

  #[ORM\Column(length: 320, unique: true)]
  private ?string $email = null;

  #[ORM\Embedded(class: Credits::class)]
  private Credits $credits;

  public function __construct(string $fullName, string $email, int $initialCreditsAmount)
  {
    $this->updateFullName($fullName);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new InvalidArgumentException('Incorrect Email address format.');
    }
    $this->email = trim($email);

    if ($initialCreditsAmount < $this::MIN_FULLNAME_LENGTH) {
      throw new InvalidArgumentException(sprintf('Initial Credits Amount must be equal or higher than %d.', $this::MIN_FULLNAME_LENGTH));
    }
    $this->credits = new Credits($initialCreditsAmount); // The Gods of DI will forgive you if the inline class instantiation is a composition.
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id ?? '',
      'fullName' => $this->fullName,
      'email' => $this->email,
    ];
  }

  public function updateFullName(string $fullName): void
  {
    if (strlen($fullName) < $this::MIN_FULLNAME_LENGTH || !strstr($fullName, ' ')) {
      throw new InvalidArgumentException(sprintf('Full Name must contain at least %d characters and one space.', $this::MIN_FULLNAME_LENGTH));
    }
    $this->fullName = trim($fullName);
  }

  public function addCredits(int $amount): void
  {
    $this->credits->add($amount);
  }

  public function requestToRender(RenderProcessorInterface $renderProcessor): void
  {
    if (!$this->credits->canDeduct($renderProcessor->getRenderQuotation())) {
      throw new ProcessRefusalException("This user can't afford this Render request.");
    }

    $this->credits->deduct($renderProcessor->getRenderQuotation());

    $renderProcessor->processRenderRequest();
  }
}
