<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserMobileController extends AbstractController
{
    #[Route('/user/mobile', name: 'app_user_mobile')]
    public function index(): Response
    {
        return $this->render('user_mobile/index1.html.twig', [
            'controller_name' => 'UserMobileController',
        ]);
    }
}
