<?php

namespace Tests\Router\Controllers\Api;

use Skay1994\MyFramework\Attributes\Route;

#[Route('/api')]
class ApiController
{
    #[Route('/user')]
    public function getUser(): string
    {
        return 'User';
    }

    #[Route('/user', methods: 'POST')]
    public function updateUser(): string
    {
        return 'USer Updated';
    }
}