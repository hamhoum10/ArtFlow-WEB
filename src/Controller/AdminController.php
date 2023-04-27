<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Artiste;
use App\Entity\Client;
use App\Entity\User;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use App\Repository\ArtisteRepository;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/statistics', name: 'app_statistics')]
    public function someAction(ClientRepository $clientRepository, ArtisteRepository $artisteRepository, EntityManagerInterface $entityManager): Response
    {
        $num_clients = $entityManager->getRepository(Client::class)->createQueryBuilder('c')->select('count(c.id)')->getQuery()->getSingleScalarResult();
        $num_artistes = $entityManager->getRepository(Artiste::class)->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult();

        return $this->render('admin/statistics.html.twig', [
            'num_clients' => $num_clients,
            'num_artistes' => $num_artistes,
        ]);
    }

    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'admins' => $adminRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdminRepository $adminRepository, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user =new User();

        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminRepository->save($admin, true);
            $user->setUsername($admin->getUsername());
            $user->setPassword($admin->getPassword());
            $user->setEmail($admin->getEmail());
            $user->setRoles(['admin']);
            $user->setPassword( $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            ));
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Admin $admin, AdminRepository $adminRepository): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminRepository->save($admin, true);

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Admin $admin, AdminRepository $adminRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$admin->getId(), $request->request->get('_token'))) {
            $adminRepository->remove($admin, true);
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }

}
