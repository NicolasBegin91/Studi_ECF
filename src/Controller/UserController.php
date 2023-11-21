<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    #[Route('/createAdmin', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function createAdmin(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();
        if (count($users) == 0) {
            $user = new User();
            return $this->edit($request, $user, $entityManager, $userPasswordHasher, true);
        } else {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        return $this->edit($request, $user, $entityManager, $userPasswordHasher,);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, bool $needAdmin = false): Response
    {
        if (!$needAdmin)
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $newUser = $user->getId() <= 0;
        $form = $this->createForm(UserType::class, $user, [
            'passwordRequired' => $newUser,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($newUser) {
                if ($needAdmin)
                    $user->setRoles(['ROLE_ADMIN']);

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $fileName = $user->getPictureName();
                if ($fileName) {
                    //Suppression de l'image précédente si elle existe déjà
                    $currentImage = $this->getParameter('user_pictures_directory') . "/" . $fileName;
                    if (file_exists($currentImage)) {
                        unlink($currentImage);
                    }
                }

                $newFilename = 'user-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('user_pictures_directory'),
                        $newFilename
                    );
                } catch (FileException) {
                }
                $user->setPictureName($newFilename);
            }

            if ($newUser)
                $entityManager->persist($user);
            $entityManager->flush();

            if ($needAdmin)
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            else
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('helpers/global/_edit.html.twig', [
            'mode' => $newUser ? 'new' : 'edit',
            'data' => $user,
            'form' => $form,
            'destination' => 'user',
            'title' => 'utilisateur'
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {

            //Suppression de l'image utilisateur
            $fileName = $user->getPictureName();
            if ($fileName) {
                $currentImage = $this->getParameter('user_pictures_directory') . "/" . $fileName;
                if (file_exists($currentImage)) {
                    unlink($currentImage);
                }
            }

            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
