<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Artiste;
use App\Entity\Client;
use App\Entity\LignePanier;
use App\Entity\Panier;
use App\Entity\Promocode;
use App\Form\LignePanierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lignepanier')]
class LignePanierController extends AbstractController
{
    #[Route('/', name: 'app_ligne_panier_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        $lignePaniers = $entityManager
            ->getRepository(LignePanier::class)
            //->findAll();
            ->findBy(['idPanier' => $panierparclient]);

        return $this->render('ligne_panier/index.html.twig', [
            'ligne_paniers' => $lignePaniers,
        ]);
    }

    #[Route('/new', name: 'app_ligne_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lignePanier = new LignePanier();
//        $form = $this->createForm(LignePanierType::class, $lignePanier);
//        $form->handleRequest($request);

        //we extract the cart of user by his id
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        //we point on the article that we will add on cart w ywali ana access to the artist who made this article
        $article = $entityManager->getRepository(Article::class)->find(36);

        $lignePanier->setIdPanier($panierparclient);
        $lignePanier->setIdArticle( /** @var Article $article */$article);
        $lignePanier->setNomArticle($article->getNomArticle());
        $lignePanier->setDescription($article->getDescription());
        $lignePanier->setPrixUnitaire($article->getPrice());
        $lignePanier->setPrenomArtiste($article->getIdArtiste()->getLastname());
        $lignePanier->setNomArtiste($article->getIdArtiste()->getFirstname());
        $lignePanier->setQuantity(1);

        if (/*$form->isSubmitted() && $form->isValid() &&*/ $panierparclient != null && $article->getIdArtiste() != null ) {
            //quantity to add if lignepanier deja existe
            //$quantity = $form->get('quantity')->getData();

            //do lp exist si oui nzido quantity , maybe i add condition for the specific panier to udpdate not all ligne panier who have that article
            /** @var LignePanier $lpexist */ $lpexist = $entityManager->getRepository(LignePanier::class)->findOneBy(['idArticle' => $article]);
            if ($lpexist != null){
                $lpexist->setQuantity($lpexist->getQuantity()+ 1 /*$quantity*/);
                $entityManager->persist($lpexist);
                $entityManager->flush();
                var_dump($lpexist->getQuantity());
                //$addedq = " quantity added succesfuly !";
                //return $this->redirectToRoute('app_ligne_panier_index', ['DoneMsg' => $addedq], Response::HTTP_SEE_OTHER);
            }else{

            $entityManager->persist($lignePanier);
            $entityManager->flush();
            //$added ="this article is added to you're cart !";
            //return $this->redirectToRoute('app_ligne_panier_index', ['DoneMsg' => $added], Response::HTTP_SEE_OTHER);
        }}
        return $this->render('ligne_panier/new.html.twig', [
            'ligne_panier' => $lignePanier,
            //'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ligne_panier_show', methods: ['GET'])]
    public function show(LignePanier $lignePanier): Response
    {
        return $this->render('ligne_panier/show.html.twig', [
            'ligne_panier' => $lignePanier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ligne_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LignePanier $lignePanier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LignePanierType::class, $lignePanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ligne_panier/edit.html.twig', [
            'ligne_panier' => $lignePanier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_ligne_panier_delete_ajax', methods: ['POST'])]
    public function deleteAjax(Request $request, LignePanier $lignePanier, EntityManagerInterface $entityManager,SessionInterface $session): JsonResponse
    {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $entityManager->remove($lignePanier);
            $entityManager->flush();
            $etatcode = $session->get('etatcode', false);//extract lel etat mel session eli amlenlha set fi function verif eli fi promocode controller
            return new JsonResponse(['success' => true ,'state'=>$etatcode]);
        }
        return new JsonResponse(['success' => false]);

    }

    #[Route('/{id}/plus', name: 'app_ligne_panier_plus_ajax', methods: ['GET','POST'])]
    public function addByOne(Request $request, LignePanier $lignePanier, EntityManagerInterface $entityManager,SessionInterface $session): JsonResponse
    {
            $lignePanier->setQuantity($lignePanier->getQuantity()+1);
            $entityManager->persist($lignePanier);
            $entityManager->flush();
            $etatcode = $session->get('etatcode', false);//extract lel etat mel session eli amlenlha set fi function verif eli fi promocode controller
            return new JsonResponse(['success' => true ,'state'=>$etatcode]);
    }

    #[Route('/{id}/minus', name: 'app_ligne_panier_minus_ajax', methods: ['GET','POST'])]
    public function minusByOne(Request $request, LignePanier $lignePanier, EntityManagerInterface $entityManager,SessionInterface $session): JsonResponse
    {   if($lignePanier->getQuantity()>1) {
        $lignePanier->setQuantity($lignePanier->getQuantity() - 1);
        $entityManager->persist($lignePanier);
        $entityManager->flush();
        $etatcode = $session->get('etatcode', false); //extract lel etat mel session eli amlenlha set fi function verif eli fi promocode controller
        return new JsonResponse(['success' => true , 'state'=>$etatcode]);
    }
        return new JsonResponse(['success' => false]);
    }


}
