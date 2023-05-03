<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\Client;
use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\ArticleRepository;
use App\Repository\ArtisteRepository;

use App\Repository\ClientRepository;
use App\Repository\RatingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/rating')]
class RatingController extends AbstractController
{
    #[Route('/new/{rating}/{idArticle}', name: 'app_rating_new', methods: ['GET', 'POST'])]
    public function new($rating,$idArticle, Request $request, ManagerRegistry $doctrine, ArticleRepository $articleRepository, ManagerRegistry $mr, RatingRepository $ratingRepository, ArtisteRepository $artisteRepository,ClientRepository $clientRepository,SessionInterface $session): Response
    {
        $client = new Client();
        $client= $clientRepository->findOneBy(["username" => $session->get("username")]);
        $clientRepository =  $this->getDoctrine()->getRepository(Client::class);
        $ratingRepository =  $this->getDoctrine()->getRepository(Rating::class);

        $ratingentity = new Rating();
        $entityManager = $doctrine->getManager();

        $oldratingentity = $ratingRepository->findOneBy(array('id_article'=>$articleRepository->find($idArticle),'id_user'=>$artisteRepository->find(1)));

        if($oldratingentity){
            $oldratingentity->setRating((int)$rating);
        }else{
            $ratingentity->setRating((int)$rating);
            $ratingentity->setIdArticle($articleRepository->find((int)$idArticle));
            $ratingentity->setIdUser($clientRepository->findOneBy(["username" => $session->get("username")]));
            $entityManager->persist($ratingentity);
        }
        $entityManager->flush();

        $em = $mr->getManager();
        $avgrating = $em->createQuery("SELECT avg(r.rating) as avg,count(r.rating) as num FROM APP\Entity\Rating r WHERE r.id_article = :idArticle")
            ->setParameter('idArticle', $idArticle)->getResult();

        return new JsonResponse( ['success' => true,'avg' => $avgrating[0] ]);
    }
}
