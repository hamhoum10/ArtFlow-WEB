<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\Evemt;
use App\Entity\Parler;
use App\Form\EvemtType;
use App\Repository\EvemtRepository;
use App\Repository\ParlerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evemt')]
class EvemtController extends AbstractController
{
    #[Route('/', name: 'app_evemt_index', methods: ['GET'])]//artiste
    public function index(EvemtRepository $evemtRepository): Response
    {
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findAll();
        return $this->render('evemt/index.html.twig', [
            // 'evemts' => $evemtRepository->findAll(),
            'evemts' => $evemts,
        ]);
    }

    #[Route('/calendar', name: 'app_evemt_cal')]
    public function calendar(EvemtRepository $evemtRepository): Response
    {
        $evemts = $evemtRepository->findAll();

        // Create an array of events for the FullCalendar

        foreach ($evemts as $evemt) {
            $episodes[] = [
                'name' => $evemt->getName(),
                'description' => $evemt->getDescription(),
                'startHour' => $evemt->getStartHour(),
                'color' => '#257e4a',
                'prix' => $evemt->getPrix()

            ];
        }


        return $this->render('evemt/fullcalendar.html.twig', [
            'episodes' => $episodes
        ]);
    }






    #[Route('/myevent', name: 'app_evemt_myevent', methods: ['GET'])]
    public function myevent(EvemtRepository $evemtRepository): Response
    {   $artiste = $this->getDoctrine()->getRepository(Artiste::class)->find(14);
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findBy(['idArtiste' => $artiste ]);
        return $this->render('evemt/unique.html.twig', [
//            'evemts' => $evemtRepository->findAll(),
            'evemts' => $evemts,
        ]);
    }





    #[Route('/Map', name: 'app_evemt_Map', methods: ['GET'])]//artiste
    public function Map(EvemtRepository $evemtRepository): Response
    {
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findAll();
        return $this->render('evemt/Map.html.twig', [
            // 'evemts' => $evemtRepository->findAll(),
            'evemts' => $evemts,
        ]);
    }


    #[Route('/showclient', name: 'app_evemt_showclient', methods: ['GET'])]//Pour artiste
    public function showclient(EvemtRepository $evemtRepository): Response
    {
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findAll();
        return $this->render('evemt/showclient.html.twig', [
            // 'evemts' => $evemtRepository->findAll(),
            'evemts' => $evemts,
        ]);
    }

    #[Route('/admin', name: 'app_evemt_showadmin', methods: ['GET'])]
    public function admin(EvemtRepository $evemtRepository): Response
    {
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findAll();
        return $this->render('evemt/admin.html.twig', [
            // 'evemts' => $evemtRepository->findAll(),
            'evemts' => $evemts,
        ]);
    }

    #[Route('/new', name: 'app_evemt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvemtRepository $evemtRepository, EntityManagerInterface $entityManager): Response
    {
        $evemt = new Evemt();
        $form = $this->createForm(EvemtType::class, $evemt);
        $form->handleRequest($request);

//        $artiste = new Artiste();
        $artiste = $entityManager->getRepository(Artiste::class)->find(18);
        $evemt->setIdArtiste($artiste);

        if ($form->isSubmitted() && $form->isValid()) {
            $evemtRepository->save($evemt, true);
            $entityManager->persist($evemt);
            $entityManager->flush();
//            pour la notification
            $this->addFlash('succes', 'Levenement est ajoutée avec succes.');

            return $this->redirectToRoute('app_evemt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evemt/AjoutEvemt.html.twig', [
            'evemt' => $evemt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evemt_show', methods: ['GET'])]
    public function show(Evemt $evemt): Response
    {
        return $this->render('evemt/AffEvemt.html.twig', [
            'evemt' => $evemt,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evemt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evemt $evemt, EvemtRepository $evemtRepository): Response
    {
        $form = $this->createForm(EvemtType::class, $evemt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evemtRepository->save($evemt, true);

            return $this->redirectToRoute('app_evemt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evemt/Modifier.html.twig', [
            'evemt' => $evemt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evemt_delete', methods: ['POST'])]
    public function delete(Request $request, Evemt $evemt, EvemtRepository $evemtRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evemt->getId(), $request->request->get('_token'))) {
            $evemtRepository->remove($evemt, true);
        }

        return $this->redirectToRoute('app_evemt_index', [], Response::HTTP_SEE_OTHER);
    }

//    #[Route('/evemt/{id_evemt}', name: 'app_evemt_details', methods: ['GET', 'POST'])]
//    public function showEvemtDetails(Evemt $evemt,Parler $parler, ParlerRepository $parlerRepository,EntityManagerInterface $entityManager,
//              Request $request, $id_evemt): Response
//    {
//
//
//
//        $parlers = $parlerRepository->findAll();
//
//
////        return $this->render('evemt/showclient.html.twig');
//        return $this->render('evemt/showclient.html.twig', [
//            'evemt' => $evemt,
//            'parlers' => $parlers,
////            'form' => $form->createView(),
////            'post_like' => $existingLike, // Pass existing like object to template
//        ]);




}