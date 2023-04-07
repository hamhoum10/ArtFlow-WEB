<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Retour;
use App\Entity\Stock;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livraison')]
class LivraisonController extends AbstractController
{
    #[Route('/', name: 'app_livraison_index', methods: ['GET'])]
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }
    #[Route('/shift-row/{id}', name: 'shift_row1')]

    public function shiftRowAction($id)
    {
        // Retrieve the row to be shifted from the source table
        $row = $this->getDoctrine()->getRepository(Livraison::class)->find($id);

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
        $entityManager->persist($destinationRow);
        $entityManager->flush();

        // Redirect the user to the original page
        return $this->redirectToRoute('app_livraison_index');
    }
    #[Route('/shift-row/{id}', name: 'shift_row3')]

    public function shiftRowAction1($id)
    {
        // Retrieve the row to be shifted from the source table
        $row = $this->getDoctrine()->getRepository(Livraison::class)->find($id);

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
        $entityManager->persist($destinationRow);
        $entityManager->flush();

        // Redirect the user to the original page
        return $this->redirectToRoute('app_livraison_index');
    }

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraisonRepository->save($livraison, true);

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livraison_show', methods: ['GET'])]
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livraison $livraison, LivraisonRepository $livraisonRepository): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraisonRepository->save($livraison, true);

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
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
