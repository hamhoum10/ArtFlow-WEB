<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\Client;
use App\Entity\Favori;
use App\Form\FavoriType;
use App\Repository\ArticleRepository;
use App\Repository\ArtisteRepository;
use App\Repository\ClientRepository;
use App\Repository\FavoriRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/favori')]
class FavoriController extends AbstractController
{
    #[Route('/article/favoriarticle/{idArticle}', name: 'app_favori_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArtisteRepository $artisteRepository, FavoriRepository $favoriRepository, ArticleRepository $articleRepository, $idArticle, SessionInterface $session,ClientRepository $clientRepository): Response
    {
        //$client = $entityManager->getRepository(Client::class)->findby(["username"=>$session->get("username")]);
        $client = new Client();
        $client= $clientRepository->findOneBy(["username" => $session->get("username")]);
        $articlefavori = new favori();
        $articlefavori->setIdArticle($articleRepository->find($idArticle));
        $articlefavori->setIdUser($clientRepository->findOneBy(["username" => $session->get("username")]));
        if(!$favoriRepository->findOneBy(array('id_article'=>$articleRepository->find($idArticle),'id_user'=>$clientRepository->findOneBy(["username" => $session->get("username")]))))
        $favoriRepository->save($articlefavori, true);
        return $this->redirectToRoute('app_article_articlefront', ['idArticle'=>$idArticle], Response::HTTP_SEE_OTHER);

    }

    #[Route('/article/removefavori/{idArticle}', name: 'app_favori_article_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request , FavoriRepository $favoriRepository,ArtisteRepository $artisteRepository, ManagerRegistry $mr, $idArticle,ArticleRepository $articleRepository,SessionInterface $session,ClientRepository $clientRepository): Response
    {
        $em = $mr->getManager();
        $favori = $favoriRepository->findOneBy(array('id_article'=>$articleRepository->find($idArticle),'id_user'=>$clientRepository->findOneBy(["username" => $session->get("username")])));
        $em->remove($favori);
        $em->flush();
        return $this->redirectToRoute('app_article_articlefavori', ['idArticle'=>$idArticle], Response::HTTP_SEE_OTHER);
    }

}
