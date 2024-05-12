<?php

namespace Tests\Router\TestControllers;

use Skay1994\MyFramework\Attributes\Route;

#[Route(prefix: 'search')]
class ControllerWithPrefix
{

    #[Route]
    public function index(): string
    {
        return 'search users';
    }

    #[Route(methods: 'POST')]
    public function updateSearch(): string
    {
        return 'OK';
    }
}