<?php

namespace App\Controller;

use App\Entity\User\ProcessRefusalException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User\User;
use App\Service\RenderProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class UserController extends AbstractController
{
  public function __construct(private RenderProcessorInterface $renderProcessor)
  {
  }

  #[Route('/user', name: 'app_user_post', methods: ['POST'])]
  public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
  {
    $requestPayload = $request->getPayload();
    $emailInput = $requestPayload->get('email', '');

    try {
      $newUser = new User(
        $requestPayload->get('fullName', ''),
        $emailInput,
        User::INITIAL_CREDITS_AMOUNT,
      );
    } catch (InvalidArgumentException $error) {
      return $this->json([
        'error' => $error->getMessage(),
      ], 400);
    }

    if ($entityManager
      ->getRepository(User::class)
      ->emailExists($emailInput)
    ) {
      return $this->json([
        'error' => sprintf('Email "%s" already used.', $emailInput),
      ], 400);
    }

    try {
      $entityManager->persist($newUser);
      $entityManager->flush();
    } catch (\Throwable $th) {
      return $this->json([
        'error' => 'Sorry, something went wrong.',
      ], 500);
    }

    return $this->json([
      'user' => $newUser->toArray(),
    ], 201);
  }

  #[Route('/user/{id}', name: 'app_user_patch', methods: ['PATCH'])]
  public function updateFullName(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
  {
    $user = $entityManager->getRepository(User::class)->find($id);
    if (!$user) {
      return $this->json([
        'error' => sprintf('User Id %d not found.', $id),
      ], 404);
    }

    $requestPayload = $request->getPayload();
    try {
      $user->updateFullName($requestPayload->get('fullName', ''));
    } catch (InvalidArgumentException $error) {
      return $this->json([
        'error' => $error->getMessage(),
      ], 400);
    }

    try {
      $entityManager->persist($user);
      $entityManager->flush();
    } catch (\Throwable $th) {
      return $this->json([
        'error' => 'Sorry, something went wrong.',
      ], 500);
    }

    return $this->json([
      'user' => $user->toArray(),
    ], 200);
  }

  #[Route('/user/{id}/add-credits', name: 'app_user_credits_add', methods: ['POST'])]
  public function addCredits(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
  {
    $user = $entityManager->getRepository(User::class)->find($id);
    if (!$user) {
      return $this->json([
        'error' => sprintf('User Id %d not found.', $id),
      ], 404);
    }

    $requestPayload = $request->getPayload();
    $creditsAmount = $requestPayload->get('amount', 0);

    $user->addCredits($creditsAmount);

    try {
      $entityManager->persist($user);
      $entityManager->flush();
    } catch (\Throwable $th) {
      return $this->json([
        'error' => 'Sorry, something went wrong.',
      ], 500);
    }

    return $this->json([
      'message' => sprintf('%d credits added to user %d', $creditsAmount, $id),
    ], 200);
  }

  #[Route('/user/{id}/render', name: 'app_user_render_request', methods: ['POST'])]
  public function renderRequest(int $id, EntityManagerInterface $entityManager): JsonResponse
  {
    $user = $entityManager->getRepository(User::class)->find($id);
    if (!$user) {
      return $this->json([
        'error' => sprintf('User Id %d not found.', $id),
      ], 404);
    }

    $this->renderProcessor->setFilename('zipfile_from_request.zip');

    try {
      $user->requestToRender($this->renderProcessor);
      $entityManager->persist($user);
      $entityManager->flush();
    } catch(ProcessRefusalException $error) {
      return $this->json([
        'error' => $error->getMessage(),
      ], 400);
    } catch (\Throwable $th) {
      dump($th);
      return $this->json([
        'error' => 'Sorry, something went wrong.',
      ], 500);
    }

    return $this->json([
      'message' => 'Render processed',
    ], 200);
  }
}
