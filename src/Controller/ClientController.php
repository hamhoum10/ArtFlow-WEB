<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Panier;
use App\Entity\User;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


#[Route('/client')]
class ClientController extends AbstractController
{


    #[Route('/welcomepage', name: 'app_welcomepage')]
    public function welcomepage(EntityManagerInterface $entityManager,Request $request, Security $security,SessionInterface $session): Response
    {
        $user = $security->getUser();
        $session->set('username', $user->getUserIdentifier());
//        dd($user->getUserIdentifier());

        $client=$entityManager->getRepository(Client::class)->findOneBy(['username'=>$user->getUserIdentifier()]);

        return $this->render('indexFrontPage.html.twig', [
            'controller_name' => 'ClientController', 'client'=>$client,
            //'username'=>$session->get('user'),
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(EntityManagerInterface $entityManager,Request $request, Security $security): Response
    {

        $user = $security->getUser();

        $client=$entityManager->getRepository(Client::class)->findOneBy(['username'=>$user->getUserIdentifier()]);

        return $this->render('client/Profile.html.twig', [
            'controller_name' => 'ClientController', 'client'=>$client,
        ]);
    }

    #[Route('/search', name: 'searchclient', methods: ['GET'])]
    public function search(ClientRepository $clientRepository, Request $request): Response
    {


        $searchTerm = $request->query->get('q');
        $clients = $clientRepository->createQueryBuilder('p')
            ->where('p.username LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();


        return $this->render('client/index1.html.twig', [
            'clients' => $clients,
            'searchTerm' => $searchTerm,
        ]);
    }




    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, Request $request): Response
    {
        $searchTerm = $request->query->get('q');
        $client = $clientRepository->createQueryBuilder('p')
            ->where('p.username LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();

        return $this->render('client/index1.html.twig', [
            'clients' => $clientRepository->findAll(),
            'searchTerm' => $searchTerm,

        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager,SessionInterface $session): Response
    {   $panier = new Panier();
        $client = new Client();
        $user =new User();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->save($client, true);
            $user->setUsername($client->getUsername());
            $user->setPassword($client->getPassword());
            $user->setEmail($client->getEmail());
            $user->setRoles(['client']);
            $user->setPassword( $userPasswordHasher->hashPassword(
                $user,
               // PASSWORD_BCRYPT,
                $form->get('password')->getData()
            ));
            $userRepository->save($user, true);
            //Cart adding
            $session->set("username",$client->getUsername());
            $clientadded =$entityManager->getRepository(Client::class)->findOneBy(["username" => $client->getUsername()]);
            $panier->setIdClient($clientadded);
            $entityManager->persist($panier);
            $entityManager->flush();
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
    public function edit(Request $request, Client $client, ClientRepository $clientRepository,EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user=$entityManager->getRepository(User::class)->findOneBy(['username'=>$client->getUsername()]);
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
            $user->setEmail($client->getEmail());
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_welcomepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/settings.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository,EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        //$user =new User();
        $user=$entityManager->getRepository(User::class)->findOneBy(['username'=>$client->getUsername()]);
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
           // $user = $client->getUser();
           // if ($user) {
            ////    $entityManager->remove($user);
            //}
            $userRepository->remove($user, true);
            $clientRepository->remove($client, true);
           // $entityManager->flush();

        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

    }