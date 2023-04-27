<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Retour;
use App\Entity\Stock;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\LivraisonController;
use Twilio\Rest\Client;

#[Route('/commande')]
class CommandeController extends AbstractController
{private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function index1()
    {
        $tab = array();
        // Get the count of all Livraison entities
        $count1 = $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(l)')
            ->from(Livraison::class, 'l')
            ->getQuery()
            ->getSingleScalarResult();


        $count2 = $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(l)')
            ->from(Commande::class, 'l')
            ->getQuery()
            ->getSingleScalarResult();
        $count3 = $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(l)')
            ->from(Stock::class, 'l')
            ->getQuery()
            ->getSingleScalarResult();
        $count4 = $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(l)')
            ->from(Retour::class, 'l')
            ->getQuery()
            ->getSingleScalarResult();
        $tab[0] = $count1;
        $tab[1] = $count2;
        $tab[2]= $count3;
        $tab[3] = $count4;
        $request = Request::create('/query', 'GET', array('tab' => $tab));
//        $response = $this->container->get('http_kernel')->handle($request);



        return $request;

    }
    public function sendSmsMessage(Client $twilioClient,String $body,String $num):Response
    {

        $twilioClient->messages->create("+216".$num, [
            "body" => $body,
            "from" => $this->getParameter('twilio_number')
        ]);
        return new Response();
    }

    #[Route('/2', name: 'app_commande_index2', methods: ['GET'])]
    public function index2(CommandeRepository $commandeRepository): Response
    {$entityManager = $this->getDoctrine()->getManager();
        $id=16;
        $row1 = $entityManager->getRepository(Commande::class)->findAll($id);


        return $this->render('commande/index2.html.twig', [
            'commandes1' => $row1,
        ]);
    }
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {

//        $count = $this->in
        $count =$this->index1() ->query->get('tab');
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3]
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
        $destinationRow->setDateEntr($row->getCreatedAt());//****
        $destinationRow->setUserName($row->getNom());//***
        $destinationRow->setIdCommende($row);//***

        $row->setStatuLiv("ON STOCK");
        $entityManager->persist($destinationRow);
        $entityManager->flush();
        $twilioClient = new Client('AC4730297eb72be182dde74c2a2143deb8','fba49a82e157a83953c49896694c44ec');
        $test=$this-> sendSmsMessage($twilioClient,'ART_FLOW want you to know that your commande is in Stock ',$row->getNumero());


        // Redirect the user to the original page
        return $this->redirectToRoute('app_stock_index');
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeRepository $commandeRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        $count =$this->index1() ->query->get('tab');

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'countliv'=>$count[0],
            'countcom'=>$count[1],
            'countsto'=>$count[2],
            'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {   $count =$this->index1() ->query->get('tab');
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
            'countliv'=>$count[0],
            'countcom'=>$count[1],
            'countsto'=>$count[2],
            'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {   $count =$this->index1() ->query->get('tab');
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'countliv'=>$count[0],
            'countcom'=>$count[1],
            'countsto'=>$count[2],
            'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande, true);
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
