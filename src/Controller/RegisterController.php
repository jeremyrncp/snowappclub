<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory)
    {
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request): Response
    {
        $user = new User();

        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $userEntity = $this->entityManager->getRepository(User::class)->findOneBy([
                'email' => $user->getEmail()
            ]);

            if ($userEntity instanceof User) {
                $this->addFlash("danger", "Un utilisateur existe déjà avec cet email");
            } else {
                $this->addFlash("success", "Vous êtes bien inscrit, vous pouvez désormais saisir vos données");

                $user->setPassword($this->passwordHasherFactory->getPasswordHasher($user)->hash($user->getPassword()));
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }

        }

        return $this->render('register/index.html.twig', [
            'form' => $registerForm->createView(),
        ]);
    }
}
