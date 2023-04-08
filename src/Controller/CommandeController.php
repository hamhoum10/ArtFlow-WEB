<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\LignePanier;
use App\Entity\Panier;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/show', name: 'app_commande_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll(); //nbadlou hena to show the commande 7asb id or smth

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }//fi app te3i i don't need to show the user commmande but only after creating one and before payment
    #[Route('/', name: 'app_showarticles_index', methods: ['GET'])]
    public function showarticles(EntityManagerInterface $entityManager): Response
    {//tafishi les article fi interface mta commande
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        $lignePaniers = $entityManager
            ->getRepository(LignePanier::class)
            //->findAll();
            ->findBy(['idPanier' => $panierparclient]);

        return $this->render('commande/index.html.twig', [
            'ligne_paniers' => $lignePaniers,
        ]);
    }

//    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {   $client = new Client();
//        $commande = new Commande();
//        $panier = new Panier();
//        $lignepanier = new LignePanier();
//
//        //instantiate form of type Commande
//        $form = $this->createForm(CommandeType::class, $commande);
//        $form->handleRequest($request);
//
//
//
//        //PRIX TOTAL--------------------------------------------------------------
//        //we get the cart that we need ama it's better extract cart by it's user so we get the client by id w baed we get panier by that client
//        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
//        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
//        $listlignepanier =$entityManager->getRepository(LignePanier::class)->findBy(['idPanier'=> $panierparclient]); //idPanier hwa de type Panier ......
//        $prixTotal=0;
//        foreach ($listlignepanier as /** @var LignePanier $lp */ $lp) {
//                $prixTotal += $lp->getPrixUnitaire() * $lp->getQuantity();
//        }
//
//
//        //LES ATTRIBUTS DE COMMANDE that user get automaclly
//        $commande->setIdPanier($panierparclient); //wala b $panier direct
//        $commande->setTotalAmount($prixTotal);
//        $currentDate = new \DateTime();
//        $commande->setCreatedAt($currentDate);
//        $commande->setStatus("en attente");
//        //if the form is nicely filled the commande will be added
//        if ($form->isSubmitted() && $form->isValid() && $panierparclient != null) {
//            $entityManager->persist($commande);
//            $entityManager->flush();
//            $donemsg ='this commande is created with success !';
//            //maybe n7othou $commande en type static mesh nest3mlouha fi pages okhrin wala fazet render
//            return $this->redirectToRoute('app_commande_index', ['DoneMsg' => $donemsg], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('commande/new.html.twig', [
//            'commande' => $commande,
//            'form' => $form,
//        ]);
//
//
//    }
    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $commande = new Commande();

        $requestData = json_decode($request->getContent(), true);
        $firstname = trim($requestData['firstname']);
        $lastname= trim($requestData['lastname']);
        $number= trim($requestData['number']);
        $address= trim($requestData['address']);
        $codepostal= trim($requestData['codepostal']);
        $email= trim($requestData['email']);




        //PRIX TOTAL--------------------------------------------------------------
        //we get the cart that we need ama it's better extract cart by it's user so we get the client by id w baed we get panier by that client
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        $listlignepanier =$entityManager->getRepository(LignePanier::class)->findBy(['idPanier'=> $panierparclient]); //idPanier hwa de type Panier ......
        $prixTotal=0;
        foreach ($listlignepanier as /** @var LignePanier $lp */ $lp) {
            $prixTotal += $lp->getPrixUnitaire() * $lp->getQuantity();
        }


        //LES ATTRIBUTS DE COMMANDE that user get automaclly
        $commande->setIdPanier($panierparclient); //wala b $panier direct
        $commande->setTotalAmount($prixTotal);
        $currentDate = new \DateTime();
        $commande->setCreatedAt($currentDate);
        $commande->setStatus("en attente");
        //if the form is nicely filled the commande will be added
        if ($firstname != "" && $lastname != "" && $email !="" && $address !="" && $codepostal !="" && $number !="") {
            $commande->setNom($firstname);
            $commande->setPrenom($lastname);
            $commande->setCodepostal($codepostal);
            $commande->setAdresse($address);
            $commande->setNumero((int)$number);
            $entityManager->persist($commande);
            $entityManager->flush();
            return new JsonResponse( ['success' => true ]);

        }

        return new JsonResponse( ['success' => false ]);

    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

}
