<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function PHPUnit\Framework\isEmpty;

class MobilePanierController extends AbstractController
{
    //API FOR MOBILE ----------------------------------------------------------------------------------------------------------------

    #[Route('mobile/new', name: 'app_panier_mobile_new')]
    public function mobilenew(Request $request, EntityManagerInterface $entityManager, NormalizerInterface $Normalizer): Response
    {
        //https://127.0.0.1:8000/mobile/new?id_client=nbr

        $panier = new Panier();


        $client = $entityManager->getRepository(Client::class)->find($request->get('id_client'));
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        $panier->setIdClient($client);

        if ($panierparclient==null && !Empty($client)) {
            $entityManager->persist($panier);
            $entityManager->flush();
            $jsonContent = $Normalizer->normalize($panier, 'json', ['groups' => 'panier']);
            return new Response(json_encode($jsonContent));

        }else{
            //condition mta client can not occure when we integrate
            return new Response(json_encode("panier deja existe or client do not existe"));


        }
    }
}