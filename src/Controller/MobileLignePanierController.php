<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Client;
use App\Entity\LignePanier;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MobileLignePanierController extends AbstractController
{
    //Display all
    #[Route('/showLP', name: 'app_ligne_panier_showLP')]
    public function showLp(EntityManagerInterface $entityManager, NormalizerInterface $normalizer): Response
    {
        //https://127.0.0.1:8000/showLP

        $lignePaniers = $entityManager
            ->getRepository(LignePanier::class)
            ->findAll();



        $json = $normalizer->serialize($lignePaniers, 'json', ['groups' => 'lp']);

        return new Response($json);
    }

    //Display the product of the current user
    #[Route('/showLP/{id}', name: 'app_ligne_panier_showLPuser')]
    public function showLpuser(EntityManagerInterface $entityManager, NormalizerInterface $normalizer,$id): Response
    {
        https://127.0.0.1:8000/showLP/3

        $client = $entityManager->getRepository(Client::class)->find($id);
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        $lignePaniers = $entityManager
            ->getRepository(LignePanier::class)
            ->findBy(['idPanier' => $panierparclient]);



        $json = $normalizer->serialize($lignePaniers, 'json', ['groups' => 'lp']);

        return new Response($json);
    }

    //adding from the detailed interface (description) because it contains a quantity field
    #[Route('/newLP', name: 'app_ligne_panier_newLP')]
    public function new(Request $request, EntityManagerInterface $entityManager,NormalizerInterface $normalizer)
    {
        //https://127.0.0.1:8000/newLP?id-client=3&&id-article=36&&quantity=1
        $lignePanier = new LignePanier();


        //we extract the cart of user by his id
        $client = $entityManager->getRepository(Client::class)->find($request->get("id-client"));
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        //we point on the article that we will add on cart w ywali ana access to the artist who made this article
        $article = $entityManager->getRepository(Article::class)->find($request->get("id-article"));

        $lignePanier->setQuantity((int)$request->get("quantity"));
        $lignePanier->setIdPanier($panierparclient);
        $lignePanier->setIdArticle(/** @var Article $article */ $article);
        $lignePanier->setNomArticle($article->getNomArticle());
        $lignePanier->setDescription($article->getDescription());
        $lignePanier->setPrixUnitaire($article->getPrice());
        $lignePanier->setPrenomArtiste($article->getIdArtiste()->getLastname());
        $lignePanier->setNomArtiste($article->getIdArtiste()->getFirstname());


        if ($panierparclient != null && $article->getIdArtiste() != null) {

            //quantity to add if lignepanier deja existe
            //do lp exist si oui nzido quantity , maybe i add condition for the specific panier to udpdate not all ligne panier who have that article
            /** @var LignePanier $lpexist */
            $lpexist = $entityManager->getRepository(LignePanier::class)->findOneBy(['idArticle' => $article,'idPanier' => ['NOT' => $panierparclient]]);
            if ($lpexist != null) {
                $lpexist->setQuantity($lpexist->getQuantity() + (int)$request->get("quantity"));//we add the quantity the user wrote
                $entityManager->persist($lpexist);
                $entityManager->flush();
                $jsonContent = $normalizer->normalize($lpexist, 'json', ['groups' => 'lp']);
                return new Response(json_encode($jsonContent) . "Quantity added");

            } else {
                //the product dosen't existe in the cart
                $entityManager->persist($lignePanier);
                $entityManager->flush();
                $jsonContent = $normalizer->normalize($lignePanier, 'json', ['groups' => 'lp']);
                return new Response(json_encode($jsonContent) . "Item added in cart");
            }
        }
    }


    //adding from the shop ui because we can add only with 1
    #[Route('/newoneLP', name: 'app_ligne_panier_newoneLP')]
    public function newOneLP(Request $request, EntityManagerInterface $entityManager, NormalizerInterface $normalizer)
    {
        //https://127.0.0.1:8000/newoneLP?id-client=3&&id-article=36
        $lignePanier = new LignePanier();


        //we extract the cart of user by his id
        $client = $entityManager->getRepository(Client::class)->find($request->get("id-client"));
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        //we point on the article that we will add on cart w ywali ana access to the artist who made this article
        $article = $entityManager->getRepository(Article::class)->find($request->get("id-article"));

        $lignePanier->setQuantity(1);
        $lignePanier->setIdPanier($panierparclient);
        $lignePanier->setIdArticle(/** @var Article $article */ $article);
        $lignePanier->setNomArticle($article->getNomArticle());
        $lignePanier->setDescription($article->getDescription());
        $lignePanier->setPrixUnitaire($article->getPrice());
        $lignePanier->setPrenomArtiste($article->getIdArtiste()->getLastname());
        $lignePanier->setNomArtiste($article->getIdArtiste()->getFirstname());


        if ($panierparclient != null && $article->getIdArtiste() != null) {

            //quantity to add if lignepanier deja existe
            //do lp exist si oui nzido quantity , maybe i add condition for the specific panier to udpdate not all ligne panier who have that article
            /** @var LignePanier $lpexist */
            $lpexist = $entityManager->getRepository(LignePanier::class)->findOneBy(['idArticle' => $article,'idPanier' => ['NOT' => $panierparclient]]);
            if ($lpexist != null) {
                $lpexist->setQuantity($lpexist->getQuantity() + 1);//we add the quantity the user wrote
                $entityManager->persist($lpexist);
                $entityManager->flush();
                $jsonContent = $normalizer->normalize($lpexist, 'json', ['groups' => 'lp']);
                return new Response(json_encode($jsonContent) . "Quantity added");

            } else {
                //the product dosen't existe in the cart
                $entityManager->persist($lignePanier);
                $entityManager->flush();
                $jsonContent = $normalizer->normalize($lignePanier, 'json', ['groups' => 'lp']);
                return new Response(json_encode($jsonContent) . "Item added in cart");
            }
        }
    }


    #[Route('/deleteAllLP/{id}', name: 'app_ligne_panier_deleteAllLP')] //delete all
    public function deleteAllLP(EntityManagerInterface $entityManager,NormalizerInterface $normalizer,$id)
    {
        //https://127.0.0.1:8000/deleteAllLP/3

        $client = $entityManager->getRepository(Client::class)->find($id);
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        $lignePanier = $entityManager->getRepository(LignePanier::class)->findBy(['idPanier' => $panierparclient]);
        foreach ($lignePanier as $lp) {
            $entityManager->remove((object)$lp);
        }
            $entityManager->flush();
            $jsonContent = $normalizer->normalize($lignePanier, 'json', ['groups' => 'lp']);
            return new Response("cart is empty  " . json_encode($jsonContent));

    }

    // delete by article
    #[Route('/{id}/deleteLP', name: 'app_ligne_panier_deleteAllLP')] //delete all
    public function deleteLP(EntityManagerInterface $entityManager,NormalizerInterface $normalizer,$id,Request $request)
    {
        //https://127.0.0.1:8000/3/deleteLP?id-article=36

        $client = $entityManager->getRepository(Client::class)->find($id);
        $article =$entityManager->getRepository(Article::class)->find($request->get("id-article"));
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        /** @var LignePanier $lignePanier */$lignePanier = $entityManager->getRepository(LignePanier::class)->findOneBy(['idPanier' => $panierparclient ,'idArticle' => $article]);

        $entityManager->remove($lignePanier);
        $entityManager->flush();

        $jsonContent = $normalizer->normalize($lignePanier, 'json', ['groups' => 'lp']);
        return new Response("lp deleted successfully " . json_encode($jsonContent));

    }


    #[Route('/{id}/plusLP', name: 'app_ligne_panier_plusLP')]
    public function addByOneLP(Request $request, EntityManagerInterface $entityManager,$id,NormalizerInterface $normalizer)
    {
        //https://127.0.0.1:8000/3/plusLP?id-article=36

        $client = $entityManager->getRepository(Client::class)->find($id);
        $article =$entityManager->getRepository(Article::class)->find($request->get("id-article"));
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        /** @var LignePanier $lignePanier */$lignePanier = $entityManager->getRepository(LignePanier::class)->findOneBy(['idPanier' => $panierparclient ,'idArticle' => $article]);

        $lignePanier->setQuantity($lignePanier->getQuantity()+1);
        $entityManager->persist($lignePanier);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($lignePanier, 'json', ['groups' => 'lp']);
        return new Response("lp added by one successfully " . json_encode($jsonContent));

    }

    #[Route('/{id}/minusLP', name: 'app_ligne_panier_minusLP', methods: ['GET','POST'])]
    public function minusByOne(Request $request, EntityManagerInterface $entityManager,$id,NormalizerInterface $normalizer)
    {
        //https://127.0.0.1:8000/3/minusLP?id-article=36

        $client = $entityManager->getRepository(Client::class)->find($id);
        $article =$entityManager->getRepository(Article::class)->find($request->get("id-article"));
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        /** @var LignePanier $lignePanier */$lignePanier = $entityManager->getRepository(LignePanier::class)->findOneBy(['idPanier' => $panierparclient ,'idArticle' => $article]);
        if ($lignePanier->getQuantity()>1){

        $lignePanier->setQuantity($lignePanier->getQuantity()-1);
        $entityManager->persist($lignePanier);
        $entityManager->flush();

        $jsonContent = $normalizer->normalize($lignePanier, 'json', ['groups' => 'lp']);
        return new Response("lp decreased by one successfully " . json_encode($jsonContent));
        }else{

            $jsonContent = $normalizer->normalize($lignePanier, 'json', ['groups' => 'lp']);
            return new Response("can't decrease further " . json_encode($jsonContent));
        }

    }
}