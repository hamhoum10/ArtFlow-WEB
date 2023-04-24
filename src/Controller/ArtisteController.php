<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/artiste')]
class ArtisteController extends AbstractController
{
    #[Route('/', name: 'app_artiste_index', methods: ['GET'])]
    public function index(ArtisteRepository $artisteRepository): Response
    {
        return $this->render('artiste/index.html.twig', [
            'artistes' => $artisteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_artiste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArtisteRepository $artisteRepository): Response
    {
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artisteRepository->save($artiste, true);

            return $this->redirectToRoute('app_artiste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artiste/new.html.twig', [
            'artiste' => $artiste,
            'form' => $form,
        ]);
    }

    #[Route('/{idArtiste}', name: 'app_artiste_show', methods: ['GET'])]
    public function show(Artiste $artiste): Response
    {
        return $this->render('artiste/show.html.twig', [
            'artiste' => $artiste,
        ]);
    }

    #[Route('/{idArtiste}/edit', name: 'app_artiste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artiste $artiste, ArtisteRepository $artisteRepository): Response
    {
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artisteRepository->save($artiste, true);

            return $this->redirectToRoute('app_artiste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artiste/edit.html.twig', [
            'artiste' => $artiste,
            'form' => $form,
        ]);
    }

    #[Route('/{idArtiste}', name: 'app_artiste_delete', methods: ['POST'])]
    public function delete(Request $request, Artiste $artiste, ArtisteRepository $artisteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artiste->getIdArtiste(), $request->request->get('_token'))) {
            $artisteRepository->remove($artiste, true);
        }

        return $this->redirectToRoute('app_artiste_index', [], Response::HTTP_SEE_OTHER);
    }
}
