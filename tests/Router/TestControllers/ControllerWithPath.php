<?php

namespace Tests\Router\TestControllers;

use Skay1994\MyFramework\Attributes\Route;

#[Route('search')]
class ControllerWithPath
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