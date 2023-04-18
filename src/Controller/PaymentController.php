<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     * @throws ApiErrorException
     */
    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(SessionInterface $session1): Response //we go to stripe checkout url
    {
        Stripe::setApiKey('sk_test_51MgCPXKJx2ljBQl3EIxLiyZEKush4tOgcLj9PzUtbqP5vLDDOgyfRQVXxYJOJwF4w36CZpLLFBB8t71hZrTRZUCr00Q6asBogP');

        $total = $session1->get('total', false);//extract lel etat mel session eli amlenlha set fi function verif eli fi promocode controller



        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => 'Total Amount :',
                        ],
                        'unit_amount'  => $total*100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
}
    #[Route('/success-url', name: 'success_url')]
    public function successUrl(SessionInterface $session1,EntityManagerInterface $entityManager): Response
    {
        //we remove the discount after the purchase
        $total = $session1->set('etatcode', false);

        //we change the satuts of the client's commande after purchase from 'en attente' to 'Done'
        $client = $entityManager->getRepository(Client::class)->find(3); //tjib client bid 3 maybe nada send me the client when login
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        //we extract the last commande of that user
        /** @var Commande $commande */ $commande = $entityManager->getRepository(Commande::class)
        ->findBy(['idPanier' => $panierparclient, 'status' => 'en attente'], ['id' => 'DESC'], 1)[0];
        $commande->setStatus("Done");

        $entityManager->persist($commande);
        $entityManager->flush();

        return $this->render('payment/success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
}
