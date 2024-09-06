<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthController extends AbstractController
{
    #[Route('/api/login', name: 'app_auth_login')]
    public function login(#[CurrentUser] User $user, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        if (!$user) {
            return $this->json('Invalid credentials', 401);
        }

        $token = $jwtManager->create($user);

        return $this->json([
            'token' => $token
        ]);
    }
}
