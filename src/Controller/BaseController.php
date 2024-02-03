<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
readonly class BaseController
{
    public function __construct(private Environment $twig)
    {
    }

    #[Route('/', name: 'app_homepage')]
    public function __invoke(): Response
    {
        return new Response($this->twig->render('base.html.twig'));
    }
}