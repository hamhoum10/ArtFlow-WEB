<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Retour;
use App\Entity\Stock;
use App\Form\RetourType;
use App\Repository\CommandeRepository;
use App\Repository\RetourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

#[Route('/retour')]
class RetourController extends AbstractController
{ public function sendSmsMessage(Client $twilioClient,$body,$num):Response
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
    #[Route('/', name: 'app_retour_index', methods: ['GET'])]
    public function index(RetourRepository $retourRepository): Response
    { $count =$this->index1() ->query->get('tab');
        return $this->render('retour/index.html.twig', [
            'retours' => $retourRepository->findAll(),
            'countliv'=>$count[0],
            'countcom'=>$count[1],
            'countsto'=>$count[2],
            'countre'=>$count[3]
        ]);
    }
    #[Route('/shift-row/{id}', name: 'shift_row')]

    public function shiftRowAction($id,CommandeRepository $commandeRepository)
    {

        // Retrieve the row to be shifted from the source table
        $row = $this->getDoctrine()->getRepository(Retour::class)->find($id);
        $s= $commandeRepository -> find($row->getIdCommende()->getId());
        // Remove the row from the source table
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($row);
        $entityManager->flush();

        // Add the row to the destination table
        $destinationRow = new Livraison();
        $destinationRow->setArtiste($row->getArtiste()); // set the necessary columns
        $destinationRow->setAddres($row->getAddres());
        $destinationRow->setDateSort($row->getDate());
        $destinationRow->setUserName($row->getUserName());
        $destinationRow->setIdCommende($row->getIdCommende());
        $destinationRow->setNameProduit($row->getNameProduit());
        $s->setStatuLiv("ON Livraison");
        $entityManager->persist($s);
        $entityManager->persist($destinationRow);
        $entityManager->flush();
        $num = $this->getDoctrine()->getRepository(Commande::class)->find($row->getIdCommende())->getNumero();
        $twilioClient = new Client('AC4730297eb72be182dde74c2a2143deb8','fba49a82e157a83953c49896694c44ec');
        $test=$this-> sendSmsMessage($twilioClient,'ART_FLOW want you to know that your commande is in Livraison ',$num);


        // Redirect the user to the original page

        return $this->redirectToRoute('app_retour_index');

    }

    #[Route('/new', name: 'app_retour_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RetourRepository $retourRepository): Response
    {
        $retour = new Retour();
        $form = $this->createForm(RetourType::class, $retour);
        $form->handleRequest($request);
        $count =$this->index1() ->query->get('tab');

        if ($form->isSubmitted() && $form->isValid()) {
            $retourRepository->save($retour, true);

            return $this->redirectToRoute('app_retour_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('retour/new.html.twig', [
            'retour' => $retour,
            'form' => $form,
            'countliv'=>$count[0],
            'countcom'=>$count[1],
            'countsto'=>$count[2],
            'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}', name: 'app_retour_show', methods: ['GET'])]
    public function show(Retour $retour): Response
    { $count =$this->index1() ->query->get('tab');
        return $this->render('retour/show.html.twig', [
            'retour' => $retour,
            'countliv'=>$count[0],
            'countcom'=>$count[1],
            'countsto'=>$count[2],
            'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}/edit', name: 'app_retour_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Retour $retour, RetourRepository $retourRepository): Response
    { $count =$this->index1() ->query->get('tab');
        $form = $this->createForm(RetourType::class, $retour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $retourRepository->save($retour, true);

            return $this->redirectToRoute('app_retour_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('retour/edit.html.twig', [
            'retour' => $retour,
            'form' => $form,
            'countliv'=>$count[0],
            'countcom'=>$count[1],
            'countsto'=>$count[2],
            'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}', name: 'app_retour_delete', methods: ['POST'])]
    public function delete(Request $request, Retour $retour, RetourRepository $retourRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$retour->getId(), $request->request->get('_token'))) {
            $retourRepository->remove($retour, true);
        }

        return $this->redirectToRoute('app_retour_index', [], Response::HTTP_SEE_OTHER);
    }
}
