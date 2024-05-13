<?php

namespace Tests\Router\TestControllers;

use Skay1994\MyFramework\Attributes\Route;

class ControllerWithRouteParameter
{
    #[Route(path: 'search/{id}', methods: ['GET'])]
    public function index(string $id): string
    {
        return 'My id is ' . $id;
    }

    #[Route(path: 'search/{?id}', methods: ['Post'])]
    public function searchUser(string $id = 'WithoutID'): string
    {
        return 'My id is ' . $id;
    }
}