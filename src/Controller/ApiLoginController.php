<?php

namespace App\Controller;

use App\Builder\User\UserTokenBuilder;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    public function __construct(
        private readonly UserTokenBuilder $userTokenBuilder,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $userToken = $this->userTokenBuilder->build($user);
        $this->entityManager->persist($userToken);
        $this->entityManager->flush();

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $userToken->getToken(),
        ]);
    }

    #[Route('/api/access/check', name: 'api_access_check', methods: ['GET'])]
    public function accessCheck(): Response
    {
        return new Response('', Response::HTTP_OK);
    }
}
