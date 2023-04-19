<?php

namespace App\Controller;
require __DIR__ . '/vendor/autoload.php';

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

#[Route('/123')]
class ElsmsController extends AbstractController
{
    private $twilio;

    public function __construct(Client $twilio)
    {
        $this->twilio = $twilio;
    }


        public function sendSms()
    {
        $message = $this->twilio->messages->create(
            '+21650660438',
            array(
                'from' => '+12766638918', // Replace with your Twilio phone number
                'body' => 'Hello from Twilio!'
            )
        );

        return new Response('SMS sent!');
    }
#[Route('/1', name: 'app_homepage')]
public function te(){$account_sid = getenv('AC0d38c511436eadf48ac03b413f251cbb');
    $auth_token = getenv('28b08adf773c0df3f314fc80c562026a');
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

// A Twilio number you own with SMS capabilities
    $twilio_number = "+12766638918";

    $client = new Client($account_sid, $auth_token);
    $client->messages->create(
// Where to send a text message (your cell phone?)
        '+21650660438',
        array(
            'from' => $twilio_number,
            'body' => 'I sent this message in under 10 minutes!'
        )
    );}

}
