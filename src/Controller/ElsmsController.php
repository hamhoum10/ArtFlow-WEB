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
            "body" => "0000",
            "from" => $this->getParameter('twilio_number')
        ]);
        return new Response();
    }
}
