<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Form\EnchereType;
use App\Repository\EnchereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/enchere')]
class EnchereController extends AbstractController
{


    #[Route('/welcomepage', name: 'app_welcomepage')]
    public function welcomepage(EnchereRepository $enchereRepository): Response
    {
        return $this->render('enchere/frontindex.html.twig', [
            'encheres' => $enchereRepository->findAll(),


        ]);
    }




    #[Route('/{ide}', name: 'app_enchere_showfront', methods: ['GET'])]
    public function frontshow( Enchere $enchere): Response
    {
        return $this->render('enchere/frontshow.html.twig', [
            'enchere' => $enchere,

        ]);

    }



    #[Route('/{ide}', name: 'app_enchere_details', methods: ['GET'])]
    public function frontdetails(Enchere $enchere): Response
    {
        return $this->render('enchere/encheredetails.html.twig', [
            'enchere' => $enchere,
        ]);
    }





    #[Route('/', name: 'app_enchere_index', methods: ['GET'])]
    public function index(EnchereRepository $enchereRepository): Response
    {
        return $this->render('enchere/index.html.twig', [
            'encheres' => $enchereRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_enchere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EnchereRepository $enchereRepository): Response
    {
        $enchere = new Enchere();
        $form = $this->createForm(EnchereType::class, $enchere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('Image')->getData();
            if($image!=null){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $enchere->setImage($fichier);}
            $enchereRepository->save($enchere, true);

            return $this->redirectToRoute('app_enchere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enchere/new.html.twig', [
            'img'=>"",
            'enchere' => $enchere,
            'form' => $form,
        ]);
    }

    #[Route('/{ide}', name: 'app_enchere_show', methods: ['GET'])]
    public function show(Enchere $enchere): Response
    {
        return $this->render('enchere/show.html.twig', [
            'enchere' => $enchere,
        ]);
    }

    #[Route('/{ide}/edit', name: 'app_enchere_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enchere $enchere, EnchereRepository $enchereRepository): Response
    {
        $form = $this->createForm(EnchereType::class, $enchere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('Image')->getData();
            if($image!=null){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $enchere->setImage($fichier);}

            $enchereRepository->save($enchere, true);
            $this->addFlash('message','successfully!');

            return $this->redirectToRoute('app_enchere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enchere/edit.html.twig', [
            'img'=>$enchere->getImage(),
            'enchere' => $enchere,
            'form' => $form,
        ]);
    }

    #[Route('/{ide}', name: 'app_enchere_delete', methods: ['POST'])]
    public function delete(Request $request, Enchere $enchere, EnchereRepository $enchereRepository): Response
    {
        /*if ($this->isCsrfTokenValid('delete'.$enchere->getIde(), $request->request->get('_token'))) {
            $enchereRepository->remove($enchere, true);
        }*/
$enchere= $enchereRepository->find($ide);
$em =$doctrine->getManager();
$em->remove($enchere);
$em->flush();

        return $this->redirectToRoute('app_enchere_index', [], Response::HTTP_SEE_OTHER);
    }
}
