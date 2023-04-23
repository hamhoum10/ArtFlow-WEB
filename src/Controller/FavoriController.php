<?php

namespace App\Controller;

use App\Entity\Favori;
use App\Form\FavoriType;
use App\Repository\ArticleRepository;
use App\Repository\ArtisteRepository;
use App\Repository\FavoriRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/favori')]
class FavoriController extends AbstractController
{
    #[Route('/article/favoriarticle/{idArticle}', name: 'app_favori_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArtisteRepository $artisteRepository, FavoriRepository $favoriRepository, ArticleRepository $articleRepository,$idArticle): Response
    {
        $articlefavori = new favori();
        $articlefavori->setIdArticle($articleRepository->find($idArticle));
        $articlefavori->setIdUser($artisteRepository->find(1));
        if(!$favoriRepository->findOneBy(array('id_article'=>$articleRepository->find($idArticle),'id_user'=>$artisteRepository->find(1))))
        $favoriRepository->save($articlefavori, true);
        return $this->redirectToRoute('app_article_articlefront', ['idArticle'=>$idArticle], Response::HTTP_SEE_OTHER);

    }

    #[Route('/article/removefavori/{idArticle}', name: 'app_favori_article_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request , FavoriRepository $favoriRepository,ArtisteRepository $artisteRepository, ManagerRegistry $mr, $idArticle,ArticleRepository $articleRepository): Response
    {
        $em = $mr->getManager();
        $favori = $favoriRepository->findOneBy(array('id_article'=>$articleRepository->find($idArticle),'id_user'=>$artisteRepository->find(1)));
        $em->remove($favori);
        $em->flush();
        return $this->redirectToRoute('app_article_articlefavori', ['idArticle'=>$idArticle], Response::HTTP_SEE_OTHER);
    }

}
