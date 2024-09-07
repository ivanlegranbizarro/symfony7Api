<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Auth')]
class AuthController
{
    #[OA\RequestBody(
        required: true,
        description: 'Login with username and password',
        content: new OA\JsonContent(
            required: ['username', 'password'],
            properties: [
                new OA\Property(property: 'username', type: 'string', example: 'user@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'yourPassword123')
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'JWT token on successful login',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'token', type: 'string', example: 'your-jwt-token')
        ])
    )]
    #[OA\Response(
        response: 401,
        description: 'Invalid credentials',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'message', type: 'string', example: 'Invalid credentials.')
        ])
    )]
    #[Route('/api/login_check', name: 'app_auth_login', methods: ['POST'])]
    public function login()
    {
        // Este método no se ejecuta, LexikJWTAuthenticationBundle gestiona el login.
    }
}
