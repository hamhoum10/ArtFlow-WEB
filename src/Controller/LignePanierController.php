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
    //Display the product of the current user
    #[Route('/', name: 'app_ligne_panier_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        $lignePaniers = $entityManager
            ->getRepository(LignePanier::class)
            ->findBy(['idPanier' => $panierparclient]);

        //will be added in the pdf file in the future
        $session->set("ligne-panier",$lignePaniers);


        return $this->render('ligne_panier/PanierBase.html.twig', [  //ligne_panier/index.html.twig before
            'ligne_paniers' => $lignePaniers,
        ]);
    }

    //adding from the detailed interface (description) because it contains a quantity field
    #[Route('/new', name: 'app_ligne_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $lignePanier = new LignePanier();

        //extracting the quantity and if drom the description ui
        $requestData = json_decode($request->getContent(), true);
        $quantity = trim($requestData['quantity']);
        $idArticle = trim($requestData['id']);

        //we extract the cart of user by his id
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        //we point on the article that we will add on cart w ywali ana access to the artist who made this article
        $article = $entityManager->getRepository(Article::class)->find($idArticle);

//      $lignePanier->setQuantity(1); //we get the quantity from malek also
        $lignePanier->setQuantity((int)$quantity);
        $lignePanier->setIdPanier($panierparclient);
        $lignePanier->setIdArticle( /** @var Article $article */$article);
        $lignePanier->setNomArticle($article->getNomArticle());
        $lignePanier->setDescription($article->getDescription());
        $lignePanier->setPrixUnitaire($article->getPrice());
        $lignePanier->setPrenomArtiste($article->getIdArtiste()->getLastname());
        $lignePanier->setNomArtiste($article->getIdArtiste()->getFirstname());


        if ($panierparclient != null && $article->getIdArtiste() != null ) {

            //quantity to add if lignepanier deja existe
            //do lp exist si oui nzido quantity , maybe i add condition for the specific panier to udpdate not all ligne panier who have that article
            /** @var LignePanier $lpexist */ $lpexist = $entityManager->getRepository(LignePanier::class)->findOneBy(['idArticle' => $article]);
            if ($lpexist != null){
                $lpexist->setQuantity($lpexist->getQuantity()+ (int)$quantity );//we add the quantity the user wrote
                $entityManager->persist($lpexist);

            }else{
                //the product dosen't existe in the cart
                $entityManager->persist($lignePanier);
            }
            $entityManager->flush();
            return new JsonResponse( ['success' => true ]);
        }
        return new JsonResponse( ['success' => false ]);

    }

    //adding from the shop ui because we can add only with 1
    #[Route('/newone', name: 'app_ligne_panier_newone', methods: ['GET', 'POST'])]
    public function newOne(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): JsonResponse
    {
        $lignePanier = new LignePanier();

        //extracting the quantity and if from the description ui
        $requestData = json_decode($request->getContent(), true);
        $idArticle = trim($requestData['id']);

        //we extract the cart of user by his id
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        //we point on the article that we will add on cart w ywali ana access to the artist who made this article,
        $article = $entityManager->getRepository(Article::class)->find($idArticle);

        $lignePanier->setQuantity(1);
        $lignePanier->setIdPanier($panierparclient);
        $lignePanier->setIdArticle( /** @var Article $article */$article);
        $lignePanier->setNomArticle($article->getNomArticle());
        $lignePanier->setDescription($article->getDescription());
        $lignePanier->setPrixUnitaire($article->getPrice());
        $lignePanier->setPrenomArtiste($article->getIdArtiste()->getLastname());
        $lignePanier->setNomArtiste($article->getIdArtiste()->getFirstname());

        if ($panierparclient != null && $article->getIdArtiste() != null ) {

            //quantity to add if lignepanier deja existe
            //do lp exist si oui nzido quantity , maybe i add condition for the specific panier to udpdate not all ligne panier who have that article
            /** @var LignePanier $lpexist */ $lpexist = $entityManager->getRepository(LignePanier::class)->findOneBy(['idArticle' => $article]);
            if ($lpexist != null){
                $lpexist->setQuantity($lpexist->getQuantity()+ 1);
                $entityManager->persist($lpexist);

            }else{
                //the product dosen't existe in the cart
                $entityManager->persist($lignePanier);
            }
            $entityManager->flush();

            return new JsonResponse( ['success' => true ]);
        }
        return new JsonResponse( ['success' => false ]);

    }


    #[Route('/{id}', name: 'app_ligne_panier_show', methods: ['GET'])]
    public function show(LignePanier $lignePanier): Response
    {
        return $this->render('ligne_panier/show.html.twig', [
            'ligne_panier' => $lignePanier,
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

            //extract el etatcode, and we send it to the script if true the value will be calculated *0.8 in very add or minus or delete
            $etatcode = $session->get('etatcode', false);//extract lel etat mel session eli amlenlha set fi function verif eli fi promocode controller
            return new JsonResponse(['success' => true ,'state'=>$etatcode]);
    }

    #[Route('/{id}/minus', name: 'app_ligne_panier_minus_ajax', methods: ['GET','POST'])]
    public function minusByOne(Request $request, LignePanier $lignePanier, EntityManagerInterface $entityManager,SessionInterface $session): JsonResponse
    {   if($lignePanier->getQuantity()>1) {
        $lignePanier->setQuantity($lignePanier->getQuantity() - 1);
        $entityManager->persist($lignePanier);
        $entityManager->flush();
        $etatcode = $session->get('etatcode', false);
        return new JsonResponse(['success' => true , 'state'=>$etatcode]);
    }
        return new JsonResponse(['success' => false]);
    }



}
