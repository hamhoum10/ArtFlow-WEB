<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\LignePanier;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MobileCommandeController extends AbstractController
{

    //showall
    #[Route('/showC', name: 'app_commande_showc')]
    public function showc(EntityManagerInterface $entityManager,NormalizerInterface $normalizer): Response
    {
        //https://127.0.0.1:8000/showC

        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();

        $json = $normalizer->serialize($commandes, 'json', ['groups' => 'commande']);

        return new Response($json);
    }

    //show by client id
    #[Route('/showCid/{id}', name: 'app_commande_showcid')]
    public function showccid(EntityManagerInterface $entityManager,NormalizerInterface $normalizer,$id): Response
    {
        //https://127.0.0.1:8000/showCid/3

        $client = $entityManager->getRepository(Client::class)->find($id);
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findBy(["idPanier" => $panierparclient]);

        $json = $normalizer->serialize($commandes, 'json', ['groups' => 'commande']);

        return new Response($json);
    }


    #[Route('/newC', name: 'app_commande_newc')]
    public function new(Request $request, EntityManagerInterface $entityManager,NormalizerInterface $normalizer)
    {
        //https://127.0.0.1:8000/newC?id-client=3&&firstname=med&&lastname=yasuo&&email=m@gmail.com&&codepostal=8555&&number=96548745&&address=ariana

        $commande = new Commande();

        $client = $entityManager->getRepository(Client::class)->find($request->get('id_client'));
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);

        //total
        $total=0;
        $lignePaniers = $entityManager->getRepository(LignePanier::class)->findBy(['idPanier' => $panierparclient]);
        foreach ($lignePaniers as /** @var LignePanier $lp */ $lp) {
            $total0 = $lp->getQuantity() * $lp->getPrixUnitaire();
            $total += $total0;
        }

        //LES ATTRIBUTS DE COMMANDE that user get automaclly
        $commande->setIdPanier($panierparclient);
        $commande->setTotalAmount($total);
        $currentDate = new \DateTime();
        $commande->setCreatedAt($currentDate);
        $commande->setStatus("en attente");

        $firstname=$request->get("firstname");
        $lastname=$request->get("lastname");
        $codePostal=$request->get("codepostal");
        $address=$request->get("address");
        $number=$request->get("number");
        $email=$request->get("email");
        //if the form is nicely filled the commande will be added
        if ($firstname != "" && $lastname != "" && $email !="" && $address !="" && $codePostal !="" && $number !="" && $total >0) {
            $commande->setNom($firstname);
            $commande->setPrenom($lastname);
            $commande->setCodepostal($codePostal);
            $commande->setAdresse($address);
            $commande->setNumero((int)$number);
            $entityManager->persist($commande);
            $entityManager->flush();
            $json = $normalizer->serialize($commande, 'json', ['groups' => 'commande']);

            return new Response($json);

        }

        return new Response("error has occured ! maybe empty panier");

    }


    #[Route('/deleteC/{id}', name: 'app_commande_delete')]
    public function delete( EntityManagerInterface $entityManager,$id,NormalizerInterface $normalizer): Response
    {
        //https://127.0.0.1:8000/deleteC/3

        $client = $entityManager->getRepository(Client::class)->find($id);
        $panierparclient = $entityManager->getRepository(Panier::class)->findOneBy(['idClient' => $client]);
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findBy(["idPanier" => $panierparclient], ['id' => 'DESC'], 1)[0];
            $entityManager->remove($commandes);
            $entityManager->flush();


        $json = $normalizer->serialize($commandes, 'json', ['groups' => 'commande']);

        return new Response($json);
    }

}