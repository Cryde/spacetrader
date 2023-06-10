<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
class BaseController
{
    public function __construct(private readonly Environment $twig)
    {
    }

    #[Route('/', name: 'app_homepage')]
    public function __invoke(): Response
    {
        return new Response($this->twig->render('base.html.twig'));
    }
}