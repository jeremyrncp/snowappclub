<?php

namespace App\Controller;

use App\Repository\LocalisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(private readonly LocalisationRepository $localisationRepository)
    {
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'localisations' => $this->localisationRepository->findAll()
        ]);
    }
}
