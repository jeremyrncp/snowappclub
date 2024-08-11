<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SaisieController extends AbstractController
{
    #[Route('/saisie', name: 'app_saisie')]
    public function index(): Response
    {
        return $this->render('saisie/index.html.twig', [
            'controller_name' => 'SasieController',
        ]);
    }
}
