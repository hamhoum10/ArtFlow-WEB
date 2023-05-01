<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Retour;
use App\Entity\Stock;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommandeRepository;
use App\Controller\ElsmsController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PhpParser\Node\Scalar\String_;

use Twilio\Rest\Client;


#[Route('/livraison')]
class LivraisonController extends AbstractController
{

    public function sendSmsMessage(Client $twilioClient,String $body,String $num):Response
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



    #[Route('/', name: 'app_livraison_index', methods: ['GET'])]
    public function index(LivraisonRepository $livraisonRepository): Response
    {







        $count =$this->index1() ->query->get('tab');

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3]
        ]);
    }


    #[Route('/shift-row/{id}', name: 'shift_row1')]

    public function shiftRowAction($id,CommandeRepository $CommandeRepository)
    {

        // Retrieve the row to be shifted from the source table
        $row = $this->getDoctrine()->getRepository(Livraison::class)->find($id);
        $s= $CommandeRepository->find($row->getIdCommende()->getId());
        // Remove the row from the source table
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($row);
        $entityManager->flush();

        // Add the row to the destination table
        $destinationRow = new Retour();
        $destinationRow->setArtiste($row->getArtiste()); // set the necessary columns
        $destinationRow->setAddres($row->getAddres());
        $destinationRow->setDate($row->getDateSort());
        $destinationRow->setUserName($row->getUserName());
        $destinationRow->setIdCommende($row->getIdCommende());
        $destinationRow->setNameProduit($row->getNameProduit());
        $s->setStatuLiv("ON Retour");
        $entityManager->persist($destinationRow);
        $entityManager->persist($s);
        $entityManager->flush();
        $num = $this->getDoctrine()->getRepository(Commande::class)->find($row->getIdCommende())->getNumero();
        $twilioClient = new Client('AC4730297eb72be182dde74c2a2143deb8','fba49a82e157a83953c49896694c44ec');
        $test=$this-> sendSmsMessage($twilioClient,'ART_FLOW want you to know that your commande is in return ',$num);
        // Redirect the user to the original page
        return $this->redirectToRoute('app_livraison_index');
    }



        #[Route('/shift-row3/{id}', name: 'shift_row3')]

    public function shiftRowAction1($id,CommandeRepository $CommandeRepository)
    {
        $s = new Commande();
        // Retrieve the row to be shifted from the source table
        $row = $this->getDoctrine()->getRepository(Livraison::class)->find($id);
        $s= $CommandeRepository->find($row->getIdCommende()->getId());
        // Remove the row from the source table
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($row);
        $entityManager->flush();


        // Add the row to the destination table
        $destinationRow = new Stock();
        $destinationRow->setArtiste($row->getArtiste()); // set the necessary columns
        $destinationRow->setAddres($row->getAddres());
        $destinationRow->setDateEntr($row->getDateSort());
        $destinationRow->setUserName($row->getUserName());
        $destinationRow->setIdCommende($row->getIdCommende());
        $destinationRow->setNameProduit($row->getNameProduit());
        $s->setStatuLiv("ON Stock");
        $entityManager->persist($destinationRow);
        $entityManager->persist($s);
        $entityManager->flush();
        $twilioClient = new Client('AC4730297eb72be182dde74c2a2143deb8','fba49a82e157a83953c49896694c44ec');
        $num = $this->getDoctrine()->getRepository(Commande::class)->find($row->getIdCommende())->getNumero();
        $test=$this-> sendSmsMessage($twilioClient,'ART_FLOW want you to know that your commande is in Stock ',$num);

        // Redirect the user to the original page
        return $this->redirectToRoute('app_livraison_index');
    }

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
        $count =$this->index1() ->query->get('tab');

//       $test= $form->get('id_commende')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $livraisonRepository->save($livraison, true);

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}', name: 'app_livraison_show', methods: ['GET'])]
    public function show(Livraison $livraison): Response
    { $count =$this->index1() ->query->get('tab');
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livraison $livraison, LivraisonRepository $livraisonRepository): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
        $count =$this->index1() ->query->get('tab');
        if ($form->isSubmitted() && $form->isValid()) {
            $livraisonRepository->save($livraison, true);

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3]
        ]);
    }

    #[Route('/{id}', name: 'app_livraison_delete', methods: ['POST'])]
    public function delete(Request $request, Livraison $livraison, LivraisonRepository $livraisonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $livraisonRepository->remove($livraison, true);
        }

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
}
