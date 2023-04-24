<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Evemt;
use App\Entity\Parler;
use App\Form\ParlerType;
use App\Repository\ParlerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/parler')]
class ParlerController extends AbstractController
{
    #[Route('/', name: 'app_parler_index', methods: ['GET'])]
    public function index(ParlerRepository $parlerRepository): Response
    {
        return $this->render('parler/index.html.twig', [
            'parlers' => $parlerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_parler_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParlerRepository $parlerRepository,EntityManagerInterface $entityManager): Response
    {
        $parler = new Parler();
        $form = $this->createForm(ParlerType::class, $parler);
        $form->handleRequest($request);

        $client = $entityManager->getRepository(Client::class)->find(26);
        $parler->setIdClient($client);
        $evemt = $entityManager->getRepository(Evemt::class)->find(112);
        $parler->setIdEvemt($evemt);

        if ($form->isSubmitted() && $form->isValid()) {
            $parlerRepository->save($parler, true);
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_parler_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parler/AjoutParler.html.twig', [
            'parler' => $parler,
            'form' => $form,
        ]);
    }

    #[Route('/{idComment}', name: 'app_parler_show', methods: ['GET'])]
    public function show(Parler $parler): Response
    {
        return $this->render('parler/show.html.twig', [
            'parler' => $parler,
        ]);
    }

    #[Route('/{idComment}/edit', name: 'app_parler_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Parler $parler, ParlerRepository $parlerRepository): Response
    {
        $form = $this->createForm(ParlerType::class, $parler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parlerRepository->save($parler, true);

            return $this->redirectToRoute('app_parler_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parler/edit.html.twig', [
            'parler' => $parler,
            'form' => $form,
        ]);
    }

    #[Route('/{idComment}', name: 'app_parler_delete', methods: ['POST'])]
    public function delete(Request $request, Parler $parler, ParlerRepository $parlerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parler->getIdComment(), $request->request->get('_token'))) {
            $parlerRepository->remove($parler, true);
        }

        return $this->redirectToRoute('app_parler_index', [], Response::HTTP_SEE_OTHER);
    }
}
