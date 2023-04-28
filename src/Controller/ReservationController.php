<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Evemt;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EvemtRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, Session $session): Response
    {

//        return $this->render('reservation/index.html.twig', [
//            'reservations' => $reservationRepository->findAll(),
//        ]);
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findBy(['id'=>$session->get('IdEvent')]);

        $client = $this->getDoctrine()->getRepository(Client::class)->find(26);
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->findOneBy(['idClient' => $client]);
//        dd($reservation);

        foreach ($evemts as $evemt){
            $total = $reservation->getNbPlace()*$evemt->getPrix(); 

        }
//        dd($total);
        return $this->render('reservation/index.html.twig', [
            // 'evemts' => $evemtRepository->findAll(),

            'reservations' => $evemts,
            'total' => $total,
        ]);
    }



    #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository,EntityManagerInterface $entityManager,$id,Session $session): Response
    {
        $session->set('IdEvent',$id);
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        $client = $entityManager->getRepository(Client::class)->find(26);
        $reservation->setIdClient($client);

        $evemt = $entityManager->getRepository(Evemt::class)->find($id);
        $reservation->setIdEvent($evemt);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('succes', 'une reservation est ajoutée avec succes.');

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/AjoutRes.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
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

//    #[Route('/evemtRes', name: 'app_reservation_evemtRes', methods: ['GET'])]
//    public function evemtRes(EvemtRepository $evemtRepository): Response
//    {
//
//        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->find(112);
////        $client = $this->getDoctrine()->getRepository(Client::class)->find(26);
////        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->findOneBy(['idClient' => $client]);
//        return $this->render('reservation/index.html.twig', [
//            // 'evemts' => $evemtRepository->findAll(),
//            'reservations' => $evemts,
//        ]);
//    }
}
