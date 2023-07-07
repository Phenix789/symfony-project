<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', 'home')]
#[AutoconfigureTag('controller.service_arguments')]
class BugController
{
    public function __invoke(
        Request $request,
    ): Response {
        // By calling this controller, arguments resolver will be called for $request
        exit();
    }
}
