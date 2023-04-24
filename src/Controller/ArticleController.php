<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Rating;
use App\Form\ArticleRatingFormType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\ArtisteRepository;
use App\Repository\CategorieRepository;
use App\Repository\RatingRepository;
use App\Repository\FavoriRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;



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

        return $this->render('article/index.html.twig',[
            'articles' => $article,
            'Artiste' => $artiste,
            'categories' => $categories,

        ]);
    }
    #[Route('/articlefront', name: 'app_article_articlefront', methods: ['GET', 'POST'])]
    public function articlefront(
        ArticleRepository $articleRepository,
        ArtisteRepository $artisteRepository,
        CategorieRepository $categorieRepository,
        Request $request,
        FavoriRepository $favoriRepository,
        PaginatorInterface $paginator
    ): Response {
        $articles = $articleRepository->createQueryBuilder('a');

        $categories = $categorieRepository->findAll();
        $artiste = $artisteRepository->findOneBy(['username' => 'mou']);

        if ($request->isMethod("POST")) {
            $minamount = $request->get('minamount');
            $maxamount = $request->get('maxamount');

            $articles = $articleRepository->findByPriceRange(
                (float) substr($minamount, 1),
                (float) substr($maxamount, 1)
            );
        }

            $article = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            2

        );

        return $this->render('article/indexfront.html.twig', [
            'articles' => $article,
            'Artiste' => $artiste,
            'categories' => $categories,
            'nbr' => $favoriRepository->findBy(['id_user' => $artiste]),

        ]);
    }




    #[Route('/articlefavori', name: 'app_article_articlefavori', methods: ['GET', 'POST'])]
    public function articlefavori(ArticleRepository $articleRepository,ArtisteRepository $artisteRepository , CategorieRepository $categorieRepository,Request $request, FavoriRepository  $favoriRepository ): Response
    {
        # dd($articleRepository->findAll());
        $categories = $categorieRepository->findAll();
        $artiste=$artisteRepository->findBy(array('username'=>'mou'))[0];

        #dd($artiste);
        $article=new Article();
        $article=$favoriRepository->findBy(array('id_user'=>$artisteRepository->find(1)));
        if($request->isMethod("POST"))
        {
            $minamount = $request->get('minamount');
            $maxamount = $request->get('maxamount');
            #dd((float)substr($minamount, 1));

            $article=$articleRepository->findByPriceRange((float)substr($minamount, 1),(float)substr($maxamount, 1));


        }

        return $this->render('article/FavoriArticle.html.twig',[
            'articles' => $article,
            'Artiste' => $artiste,
            'categories' => $categories,

        ]);
    }










    #[Route('/description/{id}', name: 'app_article_indexdescription', methods: ['GET', 'POST'])]
    public function indexdescription(ArticleRepository $articleRepository, ArtisteRepository $artisteRepository , CategorieRepository $categorieRepository, ManagerRegistry $doctrine, Request $request, $id, RatingRepository $ratingRepository): Response
    {
        $rating = new Rating();
        $form = $this->createForm(ArticleRatingFormType::class, $rating);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);
        }


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

        if($ratingRepository->findOneBy(array('id_article'=>$articleRepository->find($id),'id_user'=>$artisteRepository->find(1))))
            $oldrating = $ratingRepository->findOneBy(array('id_article'=>$articleRepository->find($id),'id_user'=>$artisteRepository->find(1)));
        else
        {
            $oldrating = new Rating();
            $oldrating->setRating(0);
        }
        $em = $doctrine->getManager();
        $avgrating = $em->createQuery("SELECT avg(r.rating) as avg,count(r.rating) as num FROM APP\Entity\Rating r WHERE r.id_article = :idArticle")
            ->setParameter('idArticle', $id)->getResult();

        return $this->render('article/description.html.twig',[
            'article' => $article,
            'articles' => $articlee,
            'Artiste' => $artiste,
            'categories' => $categories,
            'form' => $form->createView(),
            'oldrating' => $oldrating,
            'avgrating' => $avgrating[0]
        ]);
    }













    #[Route('/recherche/{categorie}', name: 'recherchearticle', methods: ['GET', 'POST'])]
    public function recherchearticle(ArticleRepository $articleRepository,ArtisteRepository $artisteRepository , CategorieRepository $categorieRepository,$categorie,Request $request,
                                     FavoriRepository $favoriRepository,
                                     PaginatorInterface $paginator): Response
    {
        # dd($articleRepository->findAll());
        $categories = $categorieRepository->findAll();

        $articles = $articleRepository->createQueryBuilder('b')->where('b.id_categorie =:categorie')->setParameter('categorie',$categorie);

        $artiste=$artisteRepository->findBy(array('username'=>'mou'))[0];
        #dd($artiste);
        $article = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('article/indexfront.html.twig',[
            'articles' => $article,
            'Artiste' => $artiste,
            'categories' => $categories,
            'nbr' => $favoriRepository->findBy(['id_user' => $artiste])

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
