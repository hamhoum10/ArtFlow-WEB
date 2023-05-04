<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Entity\Client;
use App\Form\EnchereType;
use App\Repository\EnchereRepository;
use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;



class MobileParticipantController extends AbstractController
{

/*********************************DISPLAY AUCTIONS *************************************/


       #[Route('/affichageparticipant', name: 'mobile_list_participants')]
       public function index(ParticipantRepository $participantRepository, NormalizerInterface $normalizer): Response
       {
           $participants = $participantRepository->findAll();
           $json = $normalizer->serialize($participants, 'json', ['groups' => 'participant']);

           return new Response($json);
       }




/*********************************DISPLAY AUCTIONS BY ID *************************************/
#[Route("/participant/{idp}", name: "participant")]
public function participantId($idp, NormalizerInterface $normalizer,ParticipantRepository $participantRepository)
{
    $participant = $participantRepository->find($idp);
    $json = $normalizer->serialize($participant, 'json', ['groups' => "participant"]);
    return new Response(json_encode($json));
}



/*********************************ADD AUCTIONS *************************************/

 #[Route("addparticipant/new", name: "addparticipant")]
    public function addParticipant(Request $req, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $participant = new Participant();

        $montant = $req->get('montant');
        $clientId = $req->get('id');
        $enchereId = $req->get('ide');

        $client = $em->getRepository(Client::class)->find($clientId);
        $enchere = $em->getRepository(Enchere::class)->find($enchereId);

        $participant->setMontant($montant);
        $participant->setId($client);
        $participant->setIde($enchere);

        $em->persist($participant);
        $em->flush();

        $jsonContent = $Normalizer->normalize($participant, 'json', ['groups' => 'participant']);
        return new Response("Participant added successfully " . json_encode($jsonContent));
    }



/*********************************UPDATE AUCTIONS *************************************/
/*http://127.0.0.1:8000/editparticipant/39?id=21&ide=40&montant=666*/

 #[Route("/editparticipant/{idp}", name: "update_participant")]
 public function updateParticipant(Request $request, Participant $participant, NormalizerInterface $normalizer): Response
 {
     $entityManager = $this->getDoctrine()->getManager();

     // Get the new values from the request
     $montant = $request->get('montant');
     $clientId = $request->get('id');
     $enchereId = $request->get('ide');

     // Update the participant entity with the new values
     $participant->setMontant($montant);

     // Retrieve the client and enchere objects from the database using their IDs
     $client = $entityManager->getRepository(Client::class)->find($clientId);
     $enchere = $entityManager->getRepository(Enchere::class)->find($enchereId);

     $participant->setId($client);
     $participant->setIde($enchere);

     // Save the changes to the database
     $entityManager->flush();

     // Normalize the updated participant entity and return it as a response
     $jsonContent = $normalizer->normalize($participant, 'json', ['groups' => 'participant']);
     return new Response("Participant updated successfully " . json_encode($jsonContent));
 }


/*********************************DELETE AUCTIONS *************************************/
 #[Route("deleteparticipant/{idp}", name: "delete_participant")]
    public function deleteparticipant (Request $req, $idp, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $participant = $em->getRepository(Participant::class)->find($idp);
        $em->remove($participant);
        $em->flush();
        $jsonContent = $Normalizer->normalize($participant, 'json', ['groups' => 'participant']);
        return new Response("Participant deleted successfully " . json_encode($jsonContent));
    }






}