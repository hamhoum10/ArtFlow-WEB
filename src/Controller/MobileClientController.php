<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Client;


use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;




class MobileClientController extends AbstractController
{


    #[Route("addClient/new", name: "add_Client")]
    public function addclient(Request $req,   NormalizerInterface $Normalizer): Response
    {

        $em = $this->getDoctrine()->getManager();

        $client->setFirstname($req->get('firstname'));
        $client->setLastname($req->get('lastname'));
        $client->setAddress($req->get('address'));
        $client->setPhonenumber($req->get('phonenumber'));
        $client->setEmail($req->get('email'));
        $client->setUsername($req->get('username'));
        $client->setPassword($req->get('password'));

        $em->persist($client);
        $em->flush();



        $jsonContent = $Normalizer->normalize($client, 'json', ['groups' => 'client']);
        return new Response(json_encode($jsonContent));
    }
}
