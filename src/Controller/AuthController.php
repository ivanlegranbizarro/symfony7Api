<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class AuthController
{
    #[Route('/api/login_check', name: 'app_auth_login', methods: ['POST'])]
    public function login()
    {
        // Este método no se ejecuta, LexikJWTAuthenticationBundle gestiona el login.
    }
}
