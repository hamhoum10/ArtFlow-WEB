<?php

namespace App\Controller;

use App\Entity\Promocode;
use App\Form\PromocodeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/promocode')]
class PromocodeController extends AbstractController
{
//    #[Route('/', name: 'app_promocode_index', methods: ['GET'])]
//    public function index(EntityManagerInterface $entityManager): Response
//    {
//        $promocodes = $entityManager
//            ->getRepository(Promocode::class)
//            ->findAll();
//
//        return $this->render('promocode/index1.html.twig', [
//            'promocodes' => $promocodes,
//        ]);
//    }

    #[Route('/new', name: 'app_promocode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promocode = new Promocode();
        $form = $this->createForm(PromocodeType::class, $promocode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promocode);
            $entityManager->flush();

            return $this->redirectToRoute('app_promocode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promocode/new.html.twig', [
            'promocode' => $promocode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_promocode_show', methods: ['GET'])]
    public function show(Promocode $promocode): Response
    {
        return $this->render('promocode/show.html.twig', [
            'promocode' => $promocode,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_promocode_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Promocode $promocode, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromocodeType::class, $promocode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_promocode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promocode/edit.html.twig', [
            'promocode' => $promocode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_promocode_delete', methods: ['POST'])]
    public function delete(Request $request, Promocode $promocode, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promocode->getId(), $request->request->get('_token'))) {
            $entityManager->remove($promocode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_promocode_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/', name: 'app_promocode_verify', methods: ['POST','GET'])]
    public function verifcode(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $code = trim($requestData['code']);

        //najmou n7ot value of session w namloulo get fi project kol tantque ma5rjtsh mel app better than  static value b barshha
        $session->set('etatcode', false);
        $codeDatabase = $entityManager->getRepository(Promocode::class)->findOneBy(['code' => $code]);
        if ($codeDatabase != null) {
            $session->set('etatcode', true);
            $promocode = new Promocode();
            $randomNumber = strval(mt_rand(10000000, 99999999));
            $promocode->setCode($randomNumber);
            $entityManager->persist($promocode);
            $entityManager->flush();
            $entityManager->remove($codeDatabase);
            $entityManager->flush();
            return new JsonResponse( ['success' => true ]);
        }
        return new JsonResponse(['success' => false]);
    }


}
