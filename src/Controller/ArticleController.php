<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\ArtisteRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET', 'POST'])]
    public function index(ArticleRepository $articleRepository,ArtisteRepository $artisteRepository , CategorieRepository $categorieRepository,Request $request): Response
    {
       # dd($articleRepository->findAll());
        $categories = $categorieRepository->findAll();
        $artiste=$artisteRepository->findBy(array('username'=>'mou'))[0];

        #dd($artiste);
        $article=new Article();
          $article=$articleRepository->findAll();
        if($request->isMethod("POST"))
        {
            $minamount = $request->get('minamount');
            $maxamount = $request->get('maxamount');
            #dd((float)substr($minamount, 1));

            $article=$articleRepository->findByPriceRange((float)substr($minamount, 1),(float)substr($maxamount, 1));


        }

        return $this->render('article/indexfront.html.twig',[
            'articles' => $article,
            'Artiste' => $artiste,
            'categories' => $categories,

        ]);
    }





    #[Route('/{id}', name: 'app_article_indexdescription', methods: ['GET', 'POST'])]
    public function indexdescription(ArticleRepository $articleRepository,ArtisteRepository $artisteRepository , CategorieRepository $categorieRepository,Request $request,$id): Response
    {
        # dd($articleRepository->findAll());
        $categories = $categorieRepository->findAll();
        $artiste=$artisteRepository->findBy(array('username'=>'mou'))[0];

        #dd($artiste);
        $article=new Article();

        $article=$articleRepository->find($id);
        $articlee=$articleRepository->findAll();

        #dd($id);
        if($request->isMethod("POST"))
        {
            $minamount = $request->get('minamount');
            $maxamount = $request->get('maxamount');
            #dd((float)substr($minamount, 1));

            $article=$articleRepository->findByPriceRange((float)substr($minamount, 1),(float)substr($maxamount, 1));


        }

        return $this->render('article/description.html.twig',[
            'article' => $article,
            'articles' => $articlee,
            'Artiste' => $artiste,
            'categories' => $categories,

        ]);
    }













    #[Route('/recherche/{categorie}', name: 'recherchearticle', methods: ['GET', 'POST'])]
    public function recherchearticle(ArticleRepository $articleRepository,ArtisteRepository $artisteRepository , CategorieRepository $categorieRepository,$categorie): Response
    {
        # dd($articleRepository->findAll());
        $categories = $categorieRepository->findAll();
        $artiste=$artisteRepository->findBy(array('username'=>'mou'))[0];
        #dd($artiste);
        return $this->render('article/indexfront.html.twig',[
            'articles' => $articleRepository->findBy(array('id_categorie' =>$categorie)),
            'Artiste' => $artiste,
            'categories' => $categories,
        ]);
    }






    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('Image')->getData();
           if($image!=null){
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $article->setImage($fichier);}
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'img'=>"",
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{idArticle}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{idArticle}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('Image')->getData();
            if($image!=null){
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $article->setImage($fichier);}
            $articleRepository->save($article, true);
            $this->addFlash('message','successfully!');
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'img'=>$article->getImage(),
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{idArticle}', name: 'app_article_delete')]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository,$idArticle,ManagerRegistry $doctrine): Response
    {
       /* if ($this->isCsrfTokenValid('delete'.$article->getIdArticle(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);*/
          $article= $articleRepository->find($idArticle);
        $em =$doctrine->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_article_index');
    }


}
