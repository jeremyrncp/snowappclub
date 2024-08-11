<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiDocumentationController extends AbstractController
{
    #[Route('/api/documentation', name: 'app_api_documentation')]
    public function index(): Response
    {
        return $this->render('api_documentation/index.html.twig', [
            'controller_name' => 'ApiDocumentationController',
        ]);
    }
}
