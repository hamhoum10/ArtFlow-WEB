<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mime\Email;


class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('payment/index1.html.twig', [
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
    public function successUrl(SessionInterface $session1,EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        //we remove the discount after the purchase
        $total = $session1->set('etatcode', false);

        //we change the satuts of the client's commande after purchase from 'en attente' to 'Done'
        $client = $entityManager->getRepository(Client::class)->findby(["username"=>$session->get("username")]); //tjib client bid 3 maybe nada send me the client when login
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


    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/pdf', name: 'pdf')]
    public function pdf(SessionInterface $session , EntityManagerInterface $entityManager , MailerInterface $mailer): Response
    {



        //logo
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/front/img/artsy.jpg';
        $logoData = base64_encode(file_get_contents($logoPath));

        //current date
        $date = date("d/m/Y");

        //njbo client
        $client = $entityManager->getRepository(Client::class)->findOneby(["username"=>$session->get("username")]);

        // Copier les données du panier dans une variable temporaire
        $lignepaniers = $session->get('ligne-panier');
        $prixtotal = $session->get('total');

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('payment/pdf.html.twig', [
            'lignesPanier' => $lignepaniers,
            'total' => $prixtotal,
            'client' =>$client,
            'date' =>$date,
            'logo' =>$logoData
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $pdfContent = $dompdf->output();



        // Create the email
        $email = (new Email())
            ->from('mohamed.miaoui@esprit.tn')
            ->to($session->get("email"))
            ->subject('Your PDF attachment')
            ->attach($pdfContent);

        // Send the email
        $mailer->send($email);


        // Generate the response with the PDF content
        $response = new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="example.pdf"'
        ]);

        return $response;


    }
}
