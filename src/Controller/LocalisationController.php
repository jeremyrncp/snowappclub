<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Entity\User;
use App\Form\LocalisationType;
use App\Repository\LocalisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/localisation')]
class LocalisationController extends AbstractController
{
    #[Route('/', name: 'app_localisation_index', methods: ['GET'])]
    public function index(LocalisationRepository $localisationRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('localisation/index.html.twig', [
            'localisations' => $localisationRepository->findBy(['user' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_localisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $localisation = new Localisation();
        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            $localisation->setUser($user);

            $entityManager->persist($localisation);
            $entityManager->flush();

            return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('localisation/new.html.twig', [
            'localisation' => $localisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localisation_show', methods: ['GET'])]
    public function show(Localisation $localisation): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $localisation->getUser()) {
            throw new UnauthorizedHttpException("Vous n'êtes pas authorisé");
        }

        return $this->render('localisation/show.html.twig', [
            'localisation' => $localisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_localisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localisation $localisation, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $localisation->getUser()) {
            throw new UnauthorizedHttpException("Vous n'êtes pas authorisé");
        }

        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('localisation/edit.html.twig', [
            'localisation' => $localisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localisation_delete', methods: ['POST'])]
    public function delete(Request $request, Localisation $localisation, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $localisation->getUser()) {
            throw new UnauthorizedHttpException("Vous n'êtes pas authorisé");
        }

        if ($this->isCsrfTokenValid('delete'.$localisation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($localisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
    }
}
