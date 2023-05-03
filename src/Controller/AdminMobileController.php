<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMobileController extends AbstractController
{
    #[Route('/admin/mobile', name: 'app_admin_mobile')]
    public function index(): Response
    {
        return $this->render('admin_mobile/index1.html.twig', [
            'controller_name' => 'AdminMobileController',
        ]);
    }
}
