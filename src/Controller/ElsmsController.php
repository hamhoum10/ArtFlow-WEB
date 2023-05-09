<?php

namespace App\Controller;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;


class ElsmsController extends AbstractController
{


    #[Route('/sms1', name: 'app_sms1')]

    public function sendSmsMessage1(Client $twilioClient):Response
    {


        $twilioClient->messages->create("+21650660438", [
            "body" => "ART_FLOW want you to know that your commande is in Livraison ",
            "from" => $this->getParameter('twilio_number')
        ]);
        return new Response();
    }
    #[Route('/sms2', name: 'app_sms2')]

    public function sendSmsMessage2(Client $twilioClient):Response
    {


        $twilioClient->messages->create("+21650660438", [
            "body" => "ART_FLOW want you to know that your commande is in Stock ",
            "from" => $this->getParameter('twilio_number')
        ]);
        return new Response();
    }
    #[Route('/sms3', name: 'app_sms3')]

    public function sendSmsMessage3(Client $twilioClient):Response
    {


        $twilioClient->messages->create("+21650660438", [
            "body" => "ART_FLOW want you to know that your commande is in Retour ",
            "from" => $this->getParameter('twilio_number')
        ]);
        return new Response();
    }
}
