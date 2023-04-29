<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Form\EnchereType;
use App\Repository\EnchereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;









class MobileEnchereController extends AbstractController
{

/*********************************DISPLAY AUCTIONS *************************************/
    #[Route('/affichage', name: 'mobile_list_enchere')]
    public function index(EnchereRepository $enchereRepository , NormalizerInterface $normalizer ): Response
    {
         $encheres = $enchereRepository->findAll();
          $json = $normalizer->serialize($encheres, 'json', ['groups' => "enchere"]);

         return new Response( $json);
    }


/*********************************DISPLAY AUCTIONS BY ID *************************************/
#[Route("/encheres/{ide}", name: "enchere")]
public function enchereId($ide, NormalizerInterface $normalizer, EnchereRepository $enchereRepository)
{
    $enchere = $enchereRepository->find($ide);
    $json = $normalizer->serialize($enchere, 'json', ['groups' => "enchere"]);
    return new Response(json_encode($json));
}



/*********************************ADD AUCTIONS *************************************/

 #[Route("addenchere/new", name: "addStudentJSON")]
    public function addenchere(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $enchere = new Enchere();
        $enchere->setTitre($req->get('titre'));
        $enchere->setDescription($req->get('description'));
        $enchere->setPrixdepart($req->get('prixdepart'));
      $dateString = '2023-04-29 12:00:00';
      $format = 'Y-m-d H:i:s';
      $dateTime = \DateTime::createFromFormat($format, $dateString);

       $enchere->setDatelimite($dateTime);

        $enchere->setImage($req->get('image'));


        $em->persist($enchere);
        $em->flush();

        $jsonContent = $Normalizer->normalize($enchere, 'json', ['groups' => 'enchere']);
        return new Response(json_encode($jsonContent));
    }


/*********************************UPDATE AUCTIONS *************************************/
/*http://127.0.0.1:8000/updateenchere/44?titre=adeline&description=hunting&prixdepart=100&datelimite=23/04/2023&image=img*/

 #[Route("updateenchere/{ide}", name: "updateenchere")]
    public function updateenchere(Request $req, $ide, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $enchere = $em->getRepository(Enchere::class)->find($ide);
       $enchere->setTitre($req->get('titre'));
            $enchere->setDescription($req->get('description'));
            $enchere->setPrixdepart($req->get('prixdepart'));
            $dateString = '2023-04-29 12:00:00';
            $format = 'Y-m-d H:i:s';
            $dateTime = \DateTime::createFromFormat($format, $dateString);
            $enchere->setDatelimite($dateTime);
            $enchere->setImage($req->get('image'));
            $em->flush();

        $jsonContent = $Normalizer->normalize($enchere, 'json', ['groups' => 'enchere']);
        return new Response("enchere updated successfully " . json_encode($jsonContent));
    }

/*********************************UPDATE AUCTIONS *************************************/
 #[Route("deleteenchere/{ide}", name: "deleteenchere")]
    public function deleteenchere(Request $req, $ide, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $enchere = $em->getRepository(Enchere::class)->find($ide);
        $em->remove($enchere);
        $em->flush();
        $jsonContent = $Normalizer->normalize($enchere, 'json', ['groups' => 'enchere']);
        return new Response("Enchere deleted successfully " . json_encode($jsonContent));
    }






}