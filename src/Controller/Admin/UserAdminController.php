<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

#[Route('/admin/user')]
class UserAdminController extends AbstractController
{
    #[Route('/', name: 'user_admin_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'user_admin_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newClearPassword = $form['password']->getData();
            if (!$newClearPassword) {
                throw new \Exception('Password is mandatory');
            }
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $newClearPassword
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_admin_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newClearPassword = $form['password']->getData();
            if ($newClearPassword) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $newClearPassword
                );
                $user->setPassword($hashedPassword);
            }
            $user->setRoles(['ROLE_ADMIN']);

            $entityManager->flush();

            return $this->redirectToRoute('user_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_admin_delete', methods: ['GET'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        } else {
            throw $this->createAccessDeniedException();
        }

        return $this->redirectToRoute('user_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
