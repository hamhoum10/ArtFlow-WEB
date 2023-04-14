<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\Evemt;
use App\Form\EvemtType;
use App\Repository\EvemtRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evemt')]
class EvemtController extends AbstractController
{
    #[Route('/', name: 'app_evemt_index', methods: ['GET'])]
    public function index(EvemtRepository $evemtRepository): Response
    {
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findAll();
        return $this->render('evemt/index.html.twig', [
//            'evemts' => $evemtRepository->findAll(),
            'evemts' => $evemts,
        ]);
    }
        //my events
    #[Route('/myevent', name: 'app_evemt_myevent', methods: ['GET'])]
    public function myevent(EvemtRepository $evemtRepository): Response
    {   $artiste = $this->getDoctrine()->getRepository(Artiste::class)->find(14);
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findBy(['idArtiste' => $artiste ]);
        return $this->render('evemt/index.html.twig', [
//            'evemts' => $evemtRepository->findAll(),
            'evemts' => $evemts,
        ]);
    }

    #[Route('/new', name: 'app_evemt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvemtRepository $evemtRepository,EntityManagerInterface $entityManager): Response
    {


        $evemt = new Evemt();
//        var $name = "";
//        var $description = "";
//        var $prix = "";
        $form = $this->createForm(EvemtType::class, $evemt);
        $form->handleRequest($request);

                             //  permet d'ajouter des valeurs statiques pour le moment
            $artiste = $entityManager->getRepository(Artiste::class)->find(16);
            $evemt->setIdArtiste($artiste);


        if ($form->isSubmitted() && $form->isValid()) {
//            $name =  $form->get("name");
//             $prix =  $form->get("prix");
//            $description =  $form->get("description");


           // $evemtRepository->save($evemt, true);
                                        //  permet d'ajouter des valeurs statiques pour le moment
            $entityManager->persist($evemt);
            $entityManager->flush();

            return $this->redirectToRoute('app_evemt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evemt/new.html.twig', [
            'evemt' => $evemt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evemt_show', methods: ['GET'])]
    public function show(Evemt $evemt): Response
    {
        return $this->render('evemt/show.html.twig', [
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

        return $this->renderForm('evemt/edit.html.twig', [
            'evemt' => $evemt,
            'form' => $form,
        ]);
    }
//    ecrire simple
    #[Route('/{id}/editmine', name: 'app_evemt_editmine', methods: ['GET', 'POST'])]
    public function editmine(Request $request, Evemt $evemt, EvemtRepository $evemtRepository): Response
    {
//        if ($evemt->getId()==)
        $form = $this->createForm(EvemtType::class, $evemt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evemtRepository->save($evemt, true);

            return $this->redirectToRoute('app_evemt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evemt/edit.html.twig', [
            'evemt' => $evemt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evemt_delete', methods: ['POST'])]
    public function delete(Request $request, Evemt $evemt, EvemtRepository $evemtRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evemt->getId(), $request->request->get('_token'))) {
            $evemtRepository->remove($evemt, true);
        }

        return $this->redirectToRoute('app_evemt_index', [], Response::HTTP_SEE_OTHER);
    }

//        Pour Admin
    #[Route('/show', name: 'app_evemt_show', methods: ['GET'])]
    public function afficherAdmin(EvemtRepository $evemtRepository,EntityManagerInterface $entityManager): Response
    {
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findAll();
        return $this->render('admin.html.twig', [
//            'evemts' => $evemtRepository->findAll(),
            'evemts' => $evemts,
        ]);
    }

//    #[Route('/showclient', name: 'app_evemt_showclient', methods: ['GET'])]
//    public function afficherArtiste(EvemtRepository $evemtRepository,EntityManagerInterface $entityManager): Response
//    {
//        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findId();
//        return $this->render('admin.html.twig', [
////            'evemts' => $evemtRepository->findAll(),
//            'evemts' => $evemts,
//        ]);
//    }
}
