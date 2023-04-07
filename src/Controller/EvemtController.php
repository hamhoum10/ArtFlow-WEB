<?php

namespace App\Controller;

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
//    #[Route('/', name: 'app_evemt_index', methods: ['GET'])]
//    public function index(EvemtRepository $evemtRepository): Response
//    {
//        return $this->render('evemt/index.html.twig', [
//            'evemts' => $evemtRepository->findAll(),
//        ]);
//    }
    #[Route('/', name: 'app_evemt_index', methods: ['GET'])]
    public function index(EvemtRepository $evemtRepository): Response
    {
        $evemts = $this->getDoctrine()->getRepository(Evemt::class)->findAll();
        return $this->render('evemt/index.html.twig', [
            'evemts' => $evemtRepository->findAll(),
            'evemts' => $evemts,
        ]);
    }



    #[Route('/new', name: 'app_evemt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvemtRepository $evemtRepository,EntityManagerInterface $entityManager): Response
    {
        $evemt = new Evemt();
        $form = $this->createForm(EvemtType::class, $evemt);
        $form->handleRequest($request);

        //----//
        //$evemt = new Evemt();
        //$evemt = $entityManager->getRepository(EvemtRepository::class)->find(12);
      //  $evemt->setIdArtiste($evemt);

        if ($form->isSubmitted() && $form->isValid()) {
            $evemtRepository->save($evemt, true);

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

    #[Route('/{id}', name: 'app_evemt_delete', methods: ['POST'])]
    public function delete(Request $request, Evemt $evemt, EvemtRepository $evemtRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evemt->getId(), $request->request->get('_token'))) {
            $evemtRepository->remove($evemt, true);
        }

        return $this->redirectToRoute('app_evemt_index', [], Response::HTTP_SEE_OTHER);
    }
}
