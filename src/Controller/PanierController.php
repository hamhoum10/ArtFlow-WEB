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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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

            $errorMessage = "this user already have a cart !";
            return $this->redirectToRoute('app_panier_index', [
                'error_message' => $errorMessage, // texecuti fi index.panier mesh nbadloush route that's why i added script ghadi
            ]);


        }
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
