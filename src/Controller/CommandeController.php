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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/commande')]
class CommandeController extends AbstractController
{


    #[Route('/', name: 'app_showarticles_index', methods: ['GET'])]
    public function showarticles(EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        //tafishi les article fi interface mta commande---------------

        //extraact the panier of the current user
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        $lignePaniers = $entityManager
            ->getRepository(LignePanier::class)
            ->findBy(['idPanier' => $panierparclient]);

        // we put in session the total amount and render the total to the template commndeBase

        $total=0;
        $etatcode = $session->get('etatcode', false);//extract lel etat mel session eli amlenlha set fi function verif eli fi promocode controller
        if ($etatcode===true){

            foreach ($lignePaniers as /** @var LignePanier $lp */$lp) {
                $total0 = $lp->getQuantity()*$lp->getPrixUnitaire();
                $total+=$total0*0.8; //discount
        }
         $session->set('total' , $total);
        }else {//no promocode
            foreach ($lignePaniers as /** @var LignePanier $lp */ $lp) {
                $total0 = $lp->getQuantity() * $lp->getPrixUnitaire();
                $total += $total0; //no discount
            }
            $session->set('total' , $total);
        }

        return $this->render('commande/commandeBase.html.twig', [ //ma3tesh render lel /new view w na7it el extend eli fiha
            'ligne_paniers' => $lignePaniers,
            'totalfinal' => $total
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): JsonResponse
    {
        $commande = new Commande();

        //we extract the data comming from  the script after submitting the form
        $requestData = json_decode($request->getContent(), true);
        $firstname = trim($requestData['firstname']);
        $lastname= trim($requestData['lastname']);
        $number= trim($requestData['number']);
        $address= trim($requestData['address']);
        $codePostal= trim($requestData['codePostal']);
        $email= trim($requestData['email']);




        //PRIX TOTAL--------------------------------------------------------------
        //we get the cart that we need ama it's better extract cart by it's user so we get the client by id w baed we get panier by that client
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        $listlignepanier =$entityManager->getRepository(LignePanier::class)->findBy(['idPanier'=> $panierparclient]); //idPanier hwa de type Panier ......
        $prixTotal=0;


        //we extract the total from session which i created in the above function
        $prixTotal=$session->get('total');

//        foreach ($listlignepanier as /** @var LignePanier $lp */ $lp) {
//            $prixTotal += $lp->getPrixUnitaire() * $lp->getQuantity();
//        }


        //LES ATTRIBUTS DE COMMANDE that user get automaclly
        $commande->setIdPanier($panierparclient);
        $commande->setTotalAmount($prixTotal);
        $currentDate = new \DateTime();
        $commande->setCreatedAt($currentDate);
        $commande->setStatus("en attente");
        //if the form is nicely filled the commande will be added
        if ($firstname != "" && $lastname != "" && $email !="" && $address !="" && $codePostal !="" && $number !="") {
            $commande->setNom($firstname);
            $commande->setPrenom($lastname);
            $commande->setCodepostal($codePostal);
            $commande->setAdresse($address);
            $commande->setNumero((int)$number);
            $entityManager->persist($commande);
            $entityManager->flush();
            return new JsonResponse( ['success' => true ]);

        }

        return new JsonResponse( ['success' => false ]);

    }

    //admin panel
    #[Route('/show', name: 'app_commande_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();

        return $this->render('commande/admin-order.html.twig', [ //commande/show.html.twig before
            'commandes' => $commandes,
        ]);
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
