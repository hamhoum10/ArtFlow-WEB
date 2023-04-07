<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Panier;
use App\Form\StripeType;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @throws ApiErrorException
     */
    #[Route('/pay', name: 'app_stripe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        Stripe::setApiKey('sk_test_51MgCPXKJx2ljBQl3EIxLiyZEKush4tOgcLj9PzUtbqP5vLDDOgyfRQVXxYJOJwF4w36CZpLLFBB8t71hZrTRZUCr00Q6asBogP');

        $form = $this->createForm(StripeType::class);
        $form->handleRequest($request);
        //tantque entity Commande fiha id_panier lezem njibo panier mta user eli 7shtna bih mesh nshofo total te3o
        $client = $entityManager->getRepository(Client::class)->find(3);
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        /** @var Commande $commande */ $commande = $entityManager->getRepository(Commande::class)->findOneBy(['idPanier' => $panierparclient , 'status' => 'en attente']);
        $totalprix = $commande->getTotalAmount();
        if ($form->isSubmitted() && $form->isValid() && $totalprix != null) {
            $formData = $form->getData();

            // create a charge
            /*$charge = Charge::create([
                'amount' => $totalprix, // the total amount to charge
                'currency' => 'usd',
                //'customer' => 'cus_NR5HqQQzFnoKA9', // the ID of the customer in stripe
                'source' => [
                    'number' => $formData['code'],
                    'name' => $formData['name'],
                    'cvc' => $formData['cvc'],
                    'exp_month' => $formData['expireMonth'],
                    'exp_year' => $formData['expireYear']
                ]
            ]);*/
            // create a test token for a Visa card

            try {
                $token = Token::create([
                    'card' => [
                        'number' => $formData['code'],
                        'name' => $formData['name'],
                        'cvc' => $formData['cvc'],
                        'exp_month' => $formData['expireMonth'],
                        'exp_year' => $formData['expireYear']
                    ]
                ]);

            $charge = Charge::create([
                'amount' => $totalprix * 100,
                'currency' => 'usd',
                'source' => $token->id,
                'description' => 'Test Charge'
            ]);

            $commande->setStatus("Done");
            $entityManager->persist($commande);
            $entityManager->flush();


            // handle the charge response
            if ($charge->status == 'succeeded') {
                $paymentsucceded ="payment has been established !";
                return $this->redirectToRoute('app_ligne_panier_index', ['DoneMsg' => $paymentsucceded], Response::HTTP_SEE_OTHER);
            } else {
                $paymentfailed ="payment failed !";
                return $this->redirectToRoute('app_ligne_panier_index', ['DoneMsg' => $paymentfailed], Response::HTTP_SEE_OTHER);
            }} catch (ApiErrorException $e) {
                echo "check you're credentiels !";
            }

    }
        return $this->renderForm('stripe/new.html.twig', [
            'form' => $form,
        ]);
}}