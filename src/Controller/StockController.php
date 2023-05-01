<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Retour;
use App\Entity\Stock;
use App\Entity\Commande;
use App\Form\StockType;
use App\Repository\CommandeRepository;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

#[Route('/stock')]
class StockController extends AbstractController
{ public function sendSmsMessage(Client $twilioClient,String $body,String $num):Response
{

    $twilioClient->messages->create("+216".$num, [
        "body" => $body,
        "from" => $this->getParameter('twilio_number')
    ]);
    return new Response();
}
    private $entityManager;

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

    #[Route('/', name: 'app_stock_index', methods: ['GET'])]

    public function index(StockRepository $stockRepository): Response
    { $count =$this->index1() ->query->get('tab');

        return $this->render('stock/index.html.twig', [
            'stocks' => $stockRepository->findAll(),'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3]
        ]);
    }
    #[Route('/shift-row/{id}', name: 'shift_row2')]

    public function shiftRowAction($id,CommandeRepository $commandeRepository)
    {

        // Retrieve the row to be shifted from the source table
        $row = $this->getDoctrine()->getRepository(Stock::class)->find($id);
        $s=$commandeRepository->find($row->getIdCommende()->getId());
        // Remove the row from the source table
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($row);
        $entityManager->flush();
        $num = $this->getDoctrine()->getRepository(Commande::class)->find($row->getIdCommende())->getNumero();
        $twilioClient = new Client('AC4730297eb72be182dde74c2a2143deb8','fba49a82e157a83953c49896694c44ec');
        $test=$this-> sendSmsMessage($twilioClient,'ART_FLOW want you to know that your commande is in Livraison ',$num);
        // Add the row to the destination table
        $destinationRow = new Livraison();
        $destinationRow->setArtiste($row->getArtiste());
        $destinationRow->setAddres($row->getAddres());
        $destinationRow->setDateSort($row->getDateEntr());
        $destinationRow->setUserName($row->getUserName());
        $destinationRow->setIdCommende($row->getIdCommende());
        $destinationRow->setNameProduit($row->getNameProduit());
        $entityManager->persist($destinationRow);
        $entityManager->flush();
        $s->setStatuLiv("ON Livraison");
        $entityManager->persist($s);
        $entityManager->flush();


        // Redirect the user to the original page
        return $this->redirectToRoute('app_stock_index');
    }

    #[Route('/new', name: 'app_stock_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StockRepository $stockRepository): Response
    { $count =$this->index1() ->query->get('tab');

        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockRepository->save($stock, true);

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form,'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}', name: 'app_stock_show', methods: ['GET'])]
    public function show(Stock $stock): Response
    { $count =$this->index1() ->query->get('tab');

        return $this->render('stock/show.html.twig', [
            'stock' => $stock,'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stock_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stock $stock, StockRepository $stockRepository): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockRepository->save($stock, true);

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }
        $count =$this->index1() ->query->get('tab');

        return $this->renderForm('stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}', name: 'app_stock_delete', methods: ['POST'])]
    public function delete(Request $request, Stock $stock, StockRepository $stockRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $stockRepository->remove($stock, true);
        }

        return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
