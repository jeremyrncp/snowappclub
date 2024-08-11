<?php

namespace App\Controller;

use App\Entity\SnowData;
use App\Entity\User;
use App\Form\SnowDataType;
use App\Repository\SnowDataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/snow/data')]
class SnowDataController extends AbstractController
{
    #[Route('/', name: 'app_snow_data_index', methods: ['GET'])]
    public function index(SnowDataRepository $snowDataRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('snow_data/index.html.twig', [
            'snow_datas' => $snowDataRepository
                ->createQueryBuilder("s")
                ->innerJoin("s.localisation", "sl")
                ->andWhere("sl.user = :user")
                ->orderBy("s.id", "DESC")
                ->setParameter("user", $user)
                ->getQuery()
                ->getResult()
        ]);
    }

    #[Route('/new', name: 'app_snow_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $snowDatum = new SnowData();
        $form = $this->createForm(SnowDataType::class, $snowDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($snowDatum);
            $entityManager->flush();

            return $this->redirectToRoute('app_snow_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('snow_data/new.html.twig', [
            'snow_datum' => $snowDatum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_snow_data_show', methods: ['GET'])]
    public function show(SnowData $snowDatum): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $snowDatum->getLocalisation()->getUser()) {
            throw new UnauthorizedHttpException("Vous n'êtes pas authorisé");
        }

        return $this->render('snow_data/show.html.twig', [
            'snow_datum' => $snowDatum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_snow_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SnowData $snowDatum, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $snowDatum->getLocalisation()->getUser()) {
            throw new UnauthorizedHttpException("Vous n'êtes pas authorisé");
        }

        $form = $this->createForm(SnowDataType::class, $snowDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_snow_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('snow_data/edit.html.twig', [
            'snow_datum' => $snowDatum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_snow_data_delete', methods: ['POST'])]
    public function delete(Request $request, SnowData $snowDatum, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $snowDatum->getLocalisation()->getUser()) {
            throw new UnauthorizedHttpException("Vous n'êtes pas authorisé");
        }

        if ($this->isCsrfTokenValid('delete'.$snowDatum->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($snowDatum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_snow_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
