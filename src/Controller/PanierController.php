<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Panier;
use App\Form\PanierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $paniers = $entityManager
            ->getRepository(Panier::class)
            ->findAll();

        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
        ]);
    }

    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {//7atit kol shay mta form en commentaire alakhtr panier fihesh form
        $panier = new Panier();
//        $form = $this->createForm(PanierType::class, $panier);
//        $form->handleRequest($request);

        $client = $entityManager->getRepository(Client::class)->find(4); //baed  inscrire bedhabt tejra hethi baed mat5ou id user
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        var_dump($panierparclient);
        $panier->setIdClient($client);

        if ($panierparclient==null) {
            $entityManager->persist($panier);
            $entityManager->flush();
            $successMessage = "Cart created for this User ! .";

            return $this->redirectToRoute('app_panier_index', ['success_message' => $successMessage ], Response::HTTP_SEE_OTHER);
        }else{
            //hethi methode 5ayba alkhtr to93ed tet3ad l route e5or w baed terj3 w background ywali blanc
//            return $this->render('panier/errorPanier.html.twig', [
//                'error_message' => $errorMessage,
//                'redirect_url' => '/panier' // mesh nerj3ou lel route loula /panier ama lezem n7oto fi twig code hetha window.location.href = "{{ redirect_url }}"; mesh ytexecuti
//            ]);
            // pop up
            $errorMessage = "this user already have a cart !";
            return $this->redirectToRoute('app_panier_index', [
                'error_message' => $errorMessage, // texecuti fi index.panier mesh nbadloush route that's why i added script ghadi
            ]);


        }
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($panier);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
//        }

//        return $this->renderForm('panier/new.html.twig', [
//            'panier' => $panier,
//            'form' => $form,
//        ]);
    }

    #[Route('/{idPanier}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{idPanier}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{idPanier}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getIdPanier(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }
}
