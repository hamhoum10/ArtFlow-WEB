<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Artiste;
use App\Entity\Rating;
use App\Form\ArticleRatingFormType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\ArtisteRepository;
use App\Repository\CategorieRepository;
use App\Repository\RatingRepository;
use App\Repository\FavoriRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;




#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET', 'POST'])]
    public function index(ArticleRepository $articleRepository,ArtisteRepository $artisteRepository , CategorieRepository $categorieRepository,Request $request,SessionInterface $session): Response
    {
        # dd($articleRepository->findAll());
        $categories = $categorieRepository->findAll();
//        $session->get("usernameariste");
//        $artiste=$artisteRepository->findBy(array('username'=>'mou'))[0];
        $artiste = new Artiste();
        $artiste= $artisteRepository->findOneBy(["username" => $session->get("usernameartiste")]);

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




    #[Route('/statistique', name: 'app_article_statistique', methods: ['GET', 'POST'])]
    public function statistique(ArticleRepository $articleRepository,ArtisteRepository $artisteRepository , CategorieRepository $categorieRepository,Request $request): Response
    {
        # dd($articleRepository->findAll());
        $categories = $categorieRepository->findAll();
        $artiste=$artisteRepository->findBy(array('username'=>'mou'))[0];
        $stat = $articleRepository->StatistiqueParArtiste();

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

        return $this->render('article/statistique.html.twig',[
            'articles' => $article,
            'Artiste' => $artiste,
            'categories' => $categories,
            'stat' => $stat
        ]);
    }


















    #[Route('/articlefront', name: 'app_article_articlefront', methods: ['GET', 'POST'])]
    public function articlefront(
        ArticleRepository $articleRepository,
        ArtisteRepository $artisteRepository,
        CategorieRepository $categorieRepository,
        Request $request,
        FavoriRepository $favoriRepository,
        PaginatorInterface $paginator,
        ManagerRegistry $mr
    ): Response {


        $categories = $categorieRepository->findAll();
        $artiste = $artisteRepository->findOneBy(['username' => 'mou']);

        $em = $mr->getManager();
        $articles = $articleRepository->createQueryBuilder('a');

        $keyword = null;
        $categorie = null;
        if($request->isMethod("POST"))
        {

            $minamount = $request->get('minamount');
            $minamount = (float) substr(($minamount), 1);
            $maxamount = $request->get('maxamount');
            $maxamount = (float) substr(($maxamount), 1);

            $keyword = $request->get('keyword');
            $categorie = $request->get('Category');
            if($keyword=="" && $categorie=="null")
            {
                $articles = $em->createQuery("SELECT a FROM APP\Entity\Article a WHERE a.price < :maxamount AND a.price > :minamount")
                    ->setParameter('minamount',$minamount)
                    ->setParameter('maxamount',$maxamount);
            }

            else if(($keyword==""||$keyword==null) && $categorie!="null")
            {
                $articles = $em->createQuery("SELECT a FROM APP\Entity\Article a WHERE a.price < :maxamount AND a.price > :minamount AND a.id_categorie = :categorie")
                    ->setParameter('minamount',$minamount)
                    ->setParameter('maxamount',$maxamount)
                    ->setParameter('categorie',$categorie);
            }


            else if(($keyword!=""||$keyword!=null) && $categorie=="null"){
                $articles = $em->createQuery("SELECT a FROM APP\Entity\Article a WHERE a.price < :maxamount AND a.price > :minamount AND a.nomArticle like :nomArticle")
                    ->setParameter('nomArticle', '%'.$keyword.'%')
                    ->setParameter('minamount',$minamount)
                    ->setParameter('maxamount',$maxamount);
            }
            else{
                $articles = $em->createQuery("SELECT a FROM APP\Entity\Article a WHERE a.price < :maxamount AND a.price > :minamount AND a.nomArticle like :nomArticle AND a.id_categorie = :categorie")
                    ->setParameter('nomArticle', '%'.$keyword.'%')
                    ->setParameter('categorie',$categorie)
                    ->setParameter('minamount',$minamount)
                    ->setParameter('maxamount',$maxamount);
            }

        }
        $article = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            5

        );

        return $this->render('article/indexfront.html.twig', [
            'articles' => $article,
            'Artiste' => $artiste,
            'categories' => $categories,
            'nbr' => $favoriRepository->findBy(['id_user' => $artiste]),
            'keyword' => $keyword,
            'Categorie' => $categorie

        ]);
    }


    #[Route('/show/names', name: 'app_article_names', methods: ['GET','POST'])]
    public function names(Request $request,PaginatorInterface $paginator, ManagerRegistry $mr, ArticleRepository $articleRepository): Response
    {
        $articles =$articleRepository->findAll();
        $names = array();
        foreach($articles as $articlee){
            array_push($names,$articlee->getNomArticle());
        }
        return new JsonResponse( ['success' => true,'names' => $names ]);
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
            'nbr' => $favoriRepository->findBy(['id_user' => $artiste]),


        ]);
    }










    #[Route('/description/{id}', name: 'app_article_indexdescription', methods: ['GET', 'POST'])]
    public function indexdescription(ArticleRepository $articleRepository, ArtisteRepository $artisteRepository , CategorieRepository $categorieRepository, ManagerRegistry $doctrine, Request $request, $id, RatingRepository $ratingRepository,FavoriRepository $favoriRepository,PaginatorInterface $paginator): Response
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
        $articlee = $articleRepository->createQueryBuilder('a')
            ->where('a.id_categorie = :idCategorie')
            ->andWhere('a.idArticle != :id')
            ->setParameter('id',$article->getIdArticle())
            ->setParameter('idCategorie',$article->getIdCategorie());
        $articleee = $paginator->paginate(
            $articlee,
            $request->query->getInt('page', 1),
            4

        );

        return $this->render('article/description.html.twig',[
            'article' => $article,
            'articles' => $articleee,
            'Artiste' => $artiste,
            'categories' => $categories,
            'form' => $form->createView(),
            'oldrating' => $oldrating,
            'nbr' => $favoriRepository->findBy(['id_user' => $artiste]),

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
    public function new(Request $request, ArticleRepository $articleRepository, MailerInterface $mailer,ArtisteRepository $artisteRepository,SessionInterface $session): Response
    {

        $article = new Article();
        $artiste = new Artiste();
        $artiste= $artisteRepository->findOneBy(["username" => $session->get("usernameartiste")]);

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
                $article->setImage($fichier);
                $article->setIdArtiste($artiste);
            }
            $articleRepository->save($article, true);
            //************ */ Envoi de l'e-mail*****************
            $email = (new Email())
                ->from('malek.chtioui127@gmail.com')
                ->to('malek.chtioui127@gmail.com')
                ->subject('Nouvelle Article!')
                ->html('<p>Un nouvelle Article a été soumise:</p>' .
                    '<ul>' .
                    '<li>id_article: ' . $article-> getIdArticle(). '</li>' .
                    '<li>Nom Article: ' . $article-> getNomArticle() . '</li>' .
                    '<li>Description: ' . $article->getDescription() . '</li>' .
                    '<li>artiste: ' . $article->getIdArtiste()->getUsername() . '</li>' .


                    '</ul>');


            $mailer->send($email);

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
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository,MailerInterface $mailer): Response
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

            //************ */ Envoi de l'e-mail*****************
            $email = (new Email())
                ->from('malek.chtioui127@gmail.com')
                ->to('malek.chtioui127@gmail.com')
                ->subject('update Article!')
                ->html('<p>Un nouvelle Article a été soumise:</p>' .
                    '<ul>' .
                    '<li>id_article: ' . $article-> getIdArticle(). '</li>' .
                    '<li>new Nom Article: ' . $article-> getNomArticle() . '</li>' .
                    '<li>new Description: ' . $article->getDescription() . '</li>' .
                    '<li>artiste: ' . $article->getIdArtiste()->getUsername() . '</li>' .


                    '</ul>');


            $mailer->send($email);
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