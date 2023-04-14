<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/welcomepage', name: 'app_welcomepage')]
    public function welcomepage(): Response
    {
        //SessionInterface $session
        //$id_user = $session->get('id_user');
        return $this->render('indexFrontPage.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {

        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $client = new Client();
        $user =new User();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->save($client, true);
            $user->setUsername($client->getUsername());
            $user->setPassword($client->getPassword());
            $user->setRoles(['client']);
            $user->setPassword( $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            ));
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, ClientRepository $clientRepository, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user =new User();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->save($client, true);
            $user->setUsername($client->getUsername());
            $user->setPassword($client->getPassword());
            $user->setRoles(['client']);
            $user->setPassword( $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            ));
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        //$user =new User();

        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
           // $user = $client->getUser();
           // if ($user) {
            ////    $entityManager->remove($user);
            //}
            $clientRepository->remove($client, true);
           // $entityManager->flush();

        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

    }