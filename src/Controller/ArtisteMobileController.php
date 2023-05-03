<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\User;
use App\Repository\ArtisteRepository;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArtisteMobileController extends AbstractController
{
    #[Route('/artiste/mobile', name: 'app_artiste_mobile')]
    public function index(): Response
    {
        return $this->render('artiste_mobile/index1.html.twig', [
            'controller_name' => 'ArtisteMobileController',
        ]);
    }

    #[Route('/affichageClient', name: 'mobile_list_client')]
    public function display(ClientRepository $clientRepository, NormalizerInterface $normalizer): Response
    {
        $client = $clientRepository->findAll();
        $json = $normalizer->serialize($client, 'json', ['groups' => 'client']);

        return new Response($json);
    }

    #[Route("/artiste/{id}", name: "ArtisteById")]
    public function artisteId($id, NormalizerInterface $normalizer, ArtisteRepository $artisteRepository)
    {
        $artiste = $artisteRepository->find($id);
        $json = $normalizer->serialize($artiste, 'json', ['groups' => "artiste"]);
        return new Response(json_encode($json));
    }

    #[Route("addArtiste/new", name: "add_Artiste")]
    public function addartiste(Request $req,   NormalizerInterface $Normalizer, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $em = $this->getDoctrine()->getManager();

        $artiste = new Artiste();
        $artiste->setFirstname($req->get('firstname'));
        $artiste->setLastname($req->get('lastname'));
        $artiste->setBirthplace($req->get('birthplace'));
        $artiste->setBirthdate($req->get('birthdate'));
        $artiste->setDescription($req->get('description'));
        $artiste->setImage($req->get('image'));
        $artiste->setAddress($req->get('address'));
        $artiste->setPhonenumber($req->get('phonenumber'));
        $artiste->setEmail($req->get('email'));
        $artiste->setUsername($req->get('username'));
        $artiste->setPassword($req->get('password'));

        $user =new User();
        $user->setUsername($req->get('username'));
        $user->setEmail($req->get('email'));
        $user->setRoles(['artiste']);
        $user->setPassword($userPasswordHasher->hashPassword(
            $user,$req->get('password')
        ));

//        $dateString = '2023-04-29 12:00:00';
//        $format = 'Y-m-d H:i:s';
//        $dateTime = \DateTime::createFromFormat($format, $dateString);
//
//        $artiste->setDatelimite($dateTime);
//
//        $artiste->setImage($req->get('image'));
        $em->persist($user);
        $em->persist($artiste);
        $em->flush();

        $jsonContent = $Normalizer->normalize($artiste, 'json', ['groups' => 'artiste']);
        return new Response(json_encode($jsonContent));
    }

    #[Route("updateartiste/{id}", name: "updateartiste")]
    public function updateartiste(Request $req, $id, NormalizerInterface $Normalizer, UserPasswordHasherInterface $userPasswordHasher)
    {

        $em = $this->getDoctrine()->getManager();
        $artiste = $em->getRepository(Artiste::class)->find($id);
        $artiste->setFirstname($req->get('firstname'));
        $artiste->setLastname($req->get('lastname'));
        $artiste->setBirthplace($req->get('birthplace'));
        $artiste->setBirthdate($req->get('birthdate'));
        $artiste->setDescription($req->get('description'));
        $artiste->setImage($req->get('image'));
        $artiste->setAddress($req->get('address'));
        $artiste->setPhonenumber($req->get('phonenumber'));
        $artiste->setEmail($req->get('email'));
        $artiste->setUsername($req->get('username'));
        $artiste->setPassword($req->get('password'));

        $user= $em->getRepository(User::class)->findOneBy(['username'=>$req->get('username')]);
        $user->setEmail($req->get('email'));
        $user->setUsername($req->get('username'));
        $user->setPassword($userPasswordHasher->hashPassword(
            $user,$req->get('password')
        ));

        $em->flush();

        $jsonContent = $Normalizer->normalize($artiste, 'json', ['groups' => 'artiste']);
        return new Response("artiste updated successfully " . json_encode($jsonContent));
    }

    #[Route("deleteartiste/{id}", name: "deleteartiste")]
    public function deleteartiste(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $artiste = $em->getRepository(Artiste::class)->find($id);
        if (!$artiste) {
            return new Response("Artiste not found");
        }
        $user = $em->getRepository(User::class)->findOneBy(['username'=>$artiste->getUsername()]);
        if ($user) {
            $em->remove($artiste);
            $em->remove($user);
            $em->flush();
            $jsonContent = $Normalizer->normalize($artiste, 'json', ['groups' => 'artiste']);
            return new Response("Enchere deleted successfully " . json_encode($jsonContent));
        }
        return new Response("User not found");
    }

}
