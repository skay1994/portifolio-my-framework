<?php

namespace Tests\Router\Controllers;

use Skay1994\MyFramework\Attributes\Route;

#[Route]
class TestController
{
    #[Route('/hello')]
    public function index(): string
    {
        return 'Hello World!';
    }
}