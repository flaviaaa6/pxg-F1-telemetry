<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    #[Route('/register/{id}', name: 'app_register')]
    public function register(
        $id = null,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $userRepository = $entityManager->getRepository(User::class);

        //acces autorisé seulement pour le premier compte ou pour l'admin.
        if ($userRepository->count([]) > 0) {
            $this->denyAccessUnlessGranted('ROLE_USER');
        }
        if ($id != null) {
            $user = $userRepository->find($id);

        } else {
            $user = new User();
        }
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );/** @var UserRepository $userRepository */;
            if ($userRepository->count([]) == 0) {
                $user->setRoles(['ROLE_ADMIN']);
            } elseif ($id != null) {
                if ($user->getRoles() == ['ROLE_ADMIN']) {
                    $user->setRoles(['ROLE_ADMIN']);
                }
            } else {
                $user->setRoles(['ROLE_USER']);

            }

            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'id' => $id,
        ]);
    }
}
