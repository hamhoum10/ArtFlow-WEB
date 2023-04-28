<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evemt;
use App\Repository\EvemtRepository;
use Symfony\Component\HttpFoundation\Request;
class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="app_search_evemt")
     */
    public function search(Request $request, EvemtRepository $evemtRepository): Response
    {
        $title = $request->get('title');
        $evemt = $evemtRepository->findOneBy(['name' => $title]);

        if (!$evemt) {
            // Post not found, return error response or redirect to search page
//            $categories = $categoryRepository->findAll();
//            $evemts = $evemtRepository->findAll();
            return $this->render('evemt/showclient.html.twig', [
                'keyword' => $title,
                'evemt' => $evemt,

//                'evemts' => $evemts,
            ]);
        }

//        $evemts = $evemtRepository->findAll();
        return $this->render('evemt/views.html.twig', [
            'evemt' => $evemt,

//            'evemts' => $evemts,
        ]);
    }

}
