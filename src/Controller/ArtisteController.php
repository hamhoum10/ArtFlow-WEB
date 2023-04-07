<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\User;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;


#[Route('/artiste')]
class ArtisteController extends AbstractController
{
    #[Route('/welcomepageArtiste', name: 'app_welcomepageArtiste')]
    public function welcomepage(): Response
    {
        return $this->render('ArtisteindexFrontPage.html.twig', [
            'controller_name' => 'ArtisteController',
        ]);
    }
    #[Route('/', name: 'app_artiste_index', methods: ['GET'])]
    public function index(ArtisteRepository $artisteRepository): Response
    {
        return $this->render('artiste/index.html.twig', [
            'artistes' => $artisteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_artiste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArtisteRepository $artisteRepository, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $artiste = new Artiste();
        $user =new User();

        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
            if ($file instanceof UploadedFile) {
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('images_directory'), $filename);

                //$file->move($this->getParameter('uploads'), $filename);
                $artiste->setImage($filename); // set image value to $event, not $form->getData()
            } else {
                // Set default image filename here
                $artiste->setImage('default.jpg');
            }
            $artisteRepository->save($artiste, true);
            $user->setUsername($artiste->getUsername());
            $user->setPassword($artiste->getPassword());
            $user->setRoles(['artiste']);
            $user->setPassword( $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            ));
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_artiste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artiste/new.html.twig', [
            'artiste' => $artiste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artiste_show', methods: ['GET'])]
    public function show(Artiste $artiste): Response
    {
        return $this->render('artiste/show.html.twig', [
            'artiste' => $artiste,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_artiste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artiste $artiste, ArtisteRepository $artisteRepository): Response
    {
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('Image')->getData();

            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $artiste->setImage($fichier);
            $artisteRepository->save($artiste, true);

            return $this->redirectToRoute('app_artiste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artiste/edit.html.twig', [
            'artiste' => $artiste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artiste_delete', methods: ['POST'])]
    public function delete(Request $request, Artiste $artiste, ArtisteRepository $artisteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artiste->getId(), $request->request->get('_token'))) {
            $artisteRepository->remove($artiste, true);
        }

        return $this->redirectToRoute('app_artiste_index', [], Response::HTTP_SEE_OTHER);
    }
}
