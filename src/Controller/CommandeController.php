<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\LignePanier;
use App\Entity\Panier;
use App\Entity\Stock;
use App\Form\CommandeType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function sendSmsMessage(\Twilio\Rest\Client $twilioClient, String $body, String $num):Response
    {

        $twilioClient->messages->create("+216".$num, [
            "body" => $body,
            "from" => $this->getParameter('twilio_number')
        ]);
        return new Response();
    }


    #[Route('/', name: 'app_showarticles_index', methods: ['GET'])]
    public function showarticles(EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        //tafishi les article fi interface mta commande---------------

        //extraact the panier of the current user
        $client = $entityManager->getRepository(Client::class)->findby(["username"=>$session->get("username")]); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        $lignePaniers = $entityManager
            ->getRepository(LignePanier::class)
            ->findBy(['idPanier' => $panierparclient]);

        //will be added in the pdf file in the future, we fill the session now with ligne panier so we can get the data after modification are done
        //the one in the lignepanier controller won t have the quantity changes in the pdf because the ligne panier is filled before the changes
        $session->set("ligne-panier",$lignePaniers);

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

        return $this->render('commande/commandeBase.html.twig', [
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

        $client = $entityManager->getRepository(Client::class)->findby(["username"=>$session->get("username")]);

        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);


        //we extract the total from session which i created in the above function
        $prixTotal=0;
        $prixTotal=$session->get('total');

        //email session for pdf send
        $session->set('email',$email);



        //if the form is nicely filled the commande will be added
        if ($firstname != "" && $lastname != "" && $email !="" && $address !="" && $codePostal !="" && $number !="") {
            $commande->setIdPanier($panierparclient);

            //LES ATTRIBUTS DE COMMANDE that user get automaclly
            $commande->setTotalAmount($prixTotal);
            $currentDate = new \DateTime();
            $commande->setCreatedAt($currentDate);
            $commande->setStatus("en attente");
            $commande->setStatuLiv("waiting");
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

    //admin panel
    #[Route('/showall', name: 'app_commande_show', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();


        return $this->render('commande/admin-order.html.twig', [
            'commandes' => $commandes,
        ]);
    }
    #[Route('/shift-row/{id}', name: 'shift_row4')]

    public function shiftRowAction($id)
    {
        // Retrieve the row to be shifted from the source table
        $row = $this->getDoctrine()->getRepository(Commande::class)->find($id);

        // Remove the row from the source table
        $entityManager = $this->getDoctrine()->getManager();


        // Add the row to the destination table
        $destinationRow = new Stock();
        $destinationRow->setNameProduit("test");
        $destinationRow->setArtiste("also_TEST");
        $destinationRow->setAddres($row->getAdresse());//****
        $format = 'Y-m-d H:i:s';
        $dateTime = DateTime::createFromFormat($format, $row->getCreatedAt());
        $destinationRow->setDateEntr($dateTime);//****
        $destinationRow->setUserName($row->getNom());//***
        $destinationRow->setIdCommende($row);//***

        $row->setStatuLiv("ON STOCK");
        $entityManager->persist($destinationRow);
        $entityManager->flush();
        $twilioClient = new \Twilio\Rest\Client('AC4730297eb72be182dde74c2a2143deb8','fba49a82e157a83953c49896694c44ec');
        $test=$this-> sendSmsMessage($twilioClient,'ART_FLOW want you to know that your commande is in Stock ',$row->getNumero());


        // Redirect the user to the original page
        return $this->redirectToRoute('app_stock_index');
    }


}
