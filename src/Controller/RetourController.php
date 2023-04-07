<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Retour;
use App\Form\RetourType;
use App\Repository\RetourRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/retour')]
class RetourController extends AbstractController
{
    #[Route('/', name: 'app_retour_index', methods: ['GET'])]
    public function index(RetourRepository $retourRepository): Response
    {
        return $this->render('retour/index.html.twig', [
            'retours' => $retourRepository->findAll(),
        ]);
    }
    #[Route('/shift-row/{id}', name: 'shift_row')]

    public function shiftRowAction($id)
    {
        // Retrieve the row to be shifted from the source table
        $row = $this->getDoctrine()->getRepository(Retour::class)->find($id);

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
        $entityManager->persist($destinationRow);
        $entityManager->flush();

        // Redirect the user to the original page

        return $this->redirectToRoute('app_retour_index');

    }

    #[Route('/new', name: 'app_retour_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RetourRepository $retourRepository): Response
    {
        $retour = new Retour();
        $form = $this->createForm(RetourType::class, $retour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $retourRepository->save($retour, true);

            return $this->redirectToRoute('app_retour_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('retour/new.html.twig', [
            'retour' => $retour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_retour_show', methods: ['GET'])]
    public function show(Retour $retour): Response
    {
        return $this->render('retour/show.html.twig', [
            'retour' => $retour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_retour_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Retour $retour, RetourRepository $retourRepository): Response
    {
        $form = $this->createForm(RetourType::class, $retour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $retourRepository->save($retour, true);

            return $this->redirectToRoute('app_retour_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('retour/edit.html.twig', [
            'retour' => $retour,
            'form' => $form,
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
