<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Retour;
use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stock')]
class StockController extends AbstractController
{
    #[Route('/', name: 'app_stock_index', methods: ['GET'])]
    public function index(StockRepository $stockRepository): Response
    {
        return $this->render('stock/index.html.twig', [
            'stocks' => $stockRepository->findAll(),
        ]);
    }
    #[Route('/shift-row/{id}', name: 'shift_row2')]

    public function shiftRowAction($id)
    {
        // Retrieve the row to be shifted from the source table
        $row = $this->getDoctrine()->getRepository(Stock::class)->find($id);

        // Remove the row from the source table
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($row);
        $entityManager->flush();

        // Add the row to the destination table
        $destinationRow = new Livraison();
        $destinationRow->setArtiste($row->getArtiste());
        $destinationRow->setAddres($row->getAddres());
        $destinationRow->setDateSort($row->getDateEntr());
        $destinationRow->setUserName($row->getUserName());
        $destinationRow->setIdCommende($row->getIdCommende());
        $destinationRow->setNameProduit($row->getNameProduit());
        $entityManager->persist($destinationRow);
        $entityManager->flush();

        // Redirect the user to the original page
        return $this->redirectToRoute('app_stock_index');
    }

    #[Route('/new', name: 'app_stock_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StockRepository $stockRepository): Response
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockRepository->save($stock, true);

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stock_show', methods: ['GET'])]
    public function show(Stock $stock): Response
    {
        return $this->render('stock/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stock_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stock $stock, StockRepository $stockRepository): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockRepository->save($stock, true);

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stock_delete', methods: ['POST'])]
    public function delete(Request $request, Stock $stock, StockRepository $stockRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $stockRepository->remove($stock, true);
        }

        return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
