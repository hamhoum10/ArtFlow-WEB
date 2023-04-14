<?php

namespace App\Controller;


use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Entity\Evemt;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;


use App\Controller\EvemtRepository;



#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
            'reservations' => $reservations,
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository,  EntityManagerInterface $entityManager): JsonResponse
    {
        $reservation = new Reservation();
        $requestData = json_decode($request->getContent(), true);
        var_dump($requestData);
//        $nbplace = $requestData['nbplace'];
//        $dateres= $requestData['dateres'];
        $nbplace = trim($requestData['nbplace']);
        $dateres= trim($requestData['dateres']);


//

//        $form = $this->createForm(ReservationType::class, $reservation);
//        $form->handleRequest($request);

         $evemt = new Evemt();
         $evemt = $entityManager->getRepository(Evemt::class)->find(98);
        $reservation->setIdEvent($evemt);

//  permet d'ajouter des valeurs statiques pour le moment
        $client = $entityManager->getRepository(Client::class)->find(33);
        $reservation->setIdClient($client);


        if ($nbplace != "" && $dateres != "") {
            $reservation->setNbPlace($nbplace);
            $date = DateTime::createFromFormat('Y-m-d', $dateres);
            $reservation->setDateres($date);

           // $reservationRepository->save($reservation, true);
            //  permet d'ajouter des valeurs statiques pour le moment
            $entityManager->persist($reservation);
//            $entityManager->persist($evemt);
            $entityManager->flush();

            return new JsonResponse( ['success' => true ]);

//            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return new JsonResponse( ['success' => false ]);

//        return $this->renderForm('reservation/new.html.twig', [
//            'reservation' => $reservation,
//            'form' => $form,
//        ]);
    }

    #[Route('/{idRes}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{idRes}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRes}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getIdRes(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
