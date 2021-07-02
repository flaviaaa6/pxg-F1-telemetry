<?php

namespace App\Controller;

use App\Entity\Logiciel;
use App\Entity\User;
use App\Entity\Version;
use App\Form\LogicielType;
use App\Form\UpdateUserType;
use App\Form\VersionType;
use App\Repository\UserRepository;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/new-version/{id}', name: 'new_version')]
    public function newVersion(
        $id = null,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        //créer le formulaire
        //mise a jour
        if ($id != null) {
            $versionRepository = $entityManager->getRepository(Version::class);
            $version = $versionRepository->find($id);

        } else {

            $version = new Version();
        }


        $versionForm = $this->createForm(VersionType::class, $version);


        //traiter le formulaire
        $versionForm->handleRequest($request);
        $author = $this->getUser();
        $version->setAuthor($author);

        if ($versionForm->isSubmitted() && $versionForm->isValid()) {
            $entityManager->persist($version);
            $entityManager->flush();

            //ajouter un message flash
            $this->addFlash('success', 'the version' . $version->getNumero() . ' has been successful added');

            //rediriger vers une nouvelle route
            return $this->redirectToRoute('download');
        }
        return $this->render('admin/new_version.html.twig', [
            'id' => $id,
            'versionFormView' => $versionForm->createView(),
        ]);
    }


    #[Route('/new-logiciel/{id}', name: 'new_logiciel')]
    public function newLogiciel(
        $id = null,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        //pour une mise a jour
        if ($id != null) {
            $logicielRepository = $entityManager->getRepository(Logiciel::class);
            $logiciel = $logicielRepository->find($id);

        } else {

            //créer le formulaire
            $logiciel = new Logiciel();
        }
        $logicielForm = $this->createForm(LogicielType::class, $logiciel);

        //traiter le formulaire
        $logicielForm->handleRequest($request);
        if ($logicielForm->isSubmitted() && $logicielForm->isValid()) {
            $entityManager->persist($logiciel);
            $entityManager->flush();

            //ajouter un message flash
            $this->addFlash('success', 'the software ' . $logiciel->getNom() . ' has been successful added');

            //rediriger vers une nouvelle route
            return $this->redirectToRoute('download');
        }
        return $this->render('admin/new_logiciel.html.twig', [
            'id' => $id,
            'logicielFormView' => $logicielForm->createView(),
        ]);
    }

    #[Route('/{id}/delete-version/{token}', name: 'delete_version')]
    public function delete(
        SessionInterface $session,
        $token,
        $id,
        EntityManagerInterface $entityManager,
        Request $request
    )
    {
        $versionRepository = $entityManager->getRepository(Version::class);
        $version = $versionRepository->find($id);
        if (!$version) {
            return $this->createNotFoundException("no version to delete found");
        }

        //verification du token
        $tokenDelete = $session->get($version->getId());
        if ($token !== $tokenDelete) {
            throw new \Exception('Your delete token is not valid');
        }

        $entityManager->remove($version);
        $entityManager->flush();
        $this->addFlash('success', 'the version ' . $version->getNumero() . ' has been successfully deleted.');

        return $this->redirectToRoute('download');


    }

    #[Route('/profil', name: 'profil')]
    public function Profil(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findAll();


        return $this->render('/admin/profil.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}update-profil', name: 'update_profil')]
    public function updateProfil(
        $id,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($id);

        $userForm = $this->createForm(UpdateUserType::class, $user);

        //traiter le formulaire
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            //ajouter un message flash
            $this->addFlash('success', 'the user ' . $user->getPseudo() . ' has been successful updated');

            //rediriger vers une nouvelle route
            return $this->redirectToRoute('admin_profil');
        }
        return $this->render('/admin/update_profil.html.twig', [
            'userFormView' => $userForm->createView(),

        ]);
    }


    #[Route('/{id}/delete-profil', name: 'delete_user')]
    public function deleteProfil(
        $id,
        EntityManagerInterface $entityManager,
    )
    {
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($id);
        if (!$user) {
            return $this->createNotFoundException("no user to delete found");
        }

        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'the version ' . $user->getPseudo() . ' has been successfully deleted.');

        return $this->redirectToRoute('admin_profil');
    }
}


