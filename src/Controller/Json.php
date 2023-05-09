<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Retour;
use App\Entity\Stock;
use App\Repository\CommandeRepository;
use App\Repository\LivraisonRepository;
use App\Repository\RetourRepository;
use App\Repository\StockRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
#[Route('/jyson')]
class Json extends AbstractController
{
    #[Route("/livraison", name:"liv")]
    public function showliv(LivraisonRepository $LivraisonRepository, NormalizerInterface $normalizer): Response
    {
        $livraison = $LivraisonRepository->findAll();
        $json = $normalizer->serialize($livraison, 'json', ['groups' => 'livraison']);

        return new Response($json);
    }
    #[Route("/commande", name:"comm")]
    public function showCom(CommandeRepository $CommandeRepository, NormalizerInterface $normalizer): Response
    {
        $commande = $CommandeRepository->findAll();
        $json = $normalizer->serialize($commande, 'json', ['groups' => 'commande']);

        return new Response($json);
    }
    #[Route("/stock", name:"stot")]
    public function showSto(StockRepository $StockRepository, NormalizerInterface $normalizer): Response
    {
        $stock = $StockRepository->findAll();
        $json = $normalizer->serialize($stock, 'json', ['groups' => 'stock']);

        return new Response($json);
    }
    #[Route("/retour", name:"ret")]
    public function showRte(RetourRepository $retourRepository, NormalizerInterface $normalizer): Response
    {
        $retour = $retourRepository->findAll();
        $json = $normalizer->serialize($retour, 'json', ['groups' => 'retour']);

        return new Response($json);
    }
    #[Route("/livraison/{id}", name: "livraisonid")]
    public function livraisonId($id, NormalizerInterface $normalizer, LivraisonRepository $livraisonRepository)
    {
        $livraison = $livraisonRepository->find($id);
        $json = $normalizer->serialize($livraison, 'json', ['groups' => "livraison"]);
        return new Response(json_encode($json));
    }
    #[Route("/commande/{id}", name: "commandeid")]
    public function commandeID($id, NormalizerInterface $normalizer, CommandeRepository $commandeRepository)
    {
        $commande = $commandeRepository->find($id);
        $json = $normalizer->serialize($commande, 'json', ['groups' => "commande"]);
        return new Response(json_encode($json));
    }
    #[Route("/stock/{id}", name: "stockid")]
    public function stockID($id, NormalizerInterface $normalizer, StockRepository $stockRepository)
    {
        $stock = $stockRepository->find($id);
        $json = $normalizer->serialize($stock, 'json', ['groups' => "stock"]);
        return new Response(json_encode($json));
    }
    #[Route("/retour/{id}", name: "retouid")]
    public function retouID($id, NormalizerInterface $normalizer, RetourRepository $retourRepository)
    {
        $retour = $retourRepository->find($id);
        $json = $normalizer->serialize($retour, 'json', ['groups' => "retour"]);
        return new Response(json_encode($json));
    }
    #[Route("/deleteliv/{id}", name: "deleteliv")]
    public function deleteliv(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $livraison = $em->getRepository(Livraison::class)->find($id);
        $em->remove($livraison);
        $em->flush();
        $jsonContent = $Normalizer->normalize($livraison, 'json', ['groups' => 'livraison']);
        return new Response("livraison deleted successfully " . json_encode($jsonContent));
    }
    #[Route("/deletecom/{id}", name: "deletecomm")]
    public function deletecom(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->find($id);
        $em->remove($commande);
        $em->flush();
        $jsonContent = $Normalizer->normalize($commande, 'json', ['groups' => 'commande']);
        return new Response("commande deleted successfully " . json_encode($jsonContent));
    }
    #[Route("/deletesto/{id}", name: "deletesto")]
    public function deletesto(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository(Stock::class)->find($id);
        $em->remove($stock);
        $em->flush();
        $jsonContent = $Normalizer->normalize($stock, 'json', ['groups' => 'stock']);
        return new Response("stock deleted successfully " . json_encode($jsonContent));
    }
    #[Route("/deleteret/{id}", name: "deleteret")]
    public function deleteret(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $retour = $em->getRepository(Retour::class)->find($id);
        $em->remove($retour);
        $em->flush();
        $jsonContent = $Normalizer->normalize($retour, 'json', ['groups' => 'retour']);
        return new Response("retour deleted successfully " . json_encode($jsonContent));
    }
    #[Route("/addcommande/new", name: "addcommande")]
    public function addcommande(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $commande = new Commande();
        $commande->setIdPanier($req->get('panier'));
       $commande->setPrenom($req->get('prenom'));
       $commande->setNom($req->get('nom'));
        $commande->setNumero($req->get('num'));
        $commande->setStatus($req->get('status'));
        $commande->setTotalAmount($req->get('amount'));
        $commande->setCodepostal($req->get('code'));
        $commande->setAdresse($req->get('adresse'));
        $commande->setStatuLiv($req->get('statu_liv'));
        $dateString = '2023-04-29 12:00:00';
        $format = 'Y-m-d H:i:s';
        $dateTime = \DateTimeImmutable::createFromFormat($format, $dateString);
//
        $commande->setCreatedAt($dateTime);




        $em->persist($commande);
        $em->flush();

        $jsonContent = $Normalizer->normalize($commande, 'json', ['groups' => 'commande']);
        return new Response(json_encode($jsonContent));
    }
    #[Route("/addlivraison/new", name: "addliv")]
    public function addliv(Request $req,   NormalizerInterface $Normalizer,CommandeRepository $commandeRepository)
    {$req->get('idcom');
     $idcomm = $commandeRepository->find($req->get('idcom'));

        $em = $this->getDoctrine()->getManager();
        $livraison = new Livraison();
        $livraison->setIdCommende($idcomm);
        $livraison->setNameProduit($req->get('nom'));
        $livraison->setArtiste($req->get('art'));
        $livraison->setAddres($req->get('adress'));
        $b = $req->get('dat');
        $birthdayDate = \DateTimeImmutable::createFromFormat('Y-m-d', $b);
        $livraison->setDateSort($birthdayDate);

        $livraison->setUserName($req->get('usr'));



        $em->persist($livraison);
        $em->flush();

        $jsonContent = $Normalizer->normalize($livraison, 'json', ['groups' => 'livraison']);
        return new Response(json_encode($jsonContent));
    }
    #[Route("/addstock/new", name: "addstk")]
    public function addstock(Request $req,   NormalizerInterface $Normalizer,CommandeRepository $commandeRepository)
    {
        $req->get('idcom');
        $idcomm = $commandeRepository->find($req->get('idcom'));

        $em = $this->getDoctrine()->getManager();
        $stock = new Stock();
        $stock->setIdCommende($idcomm);
        $stock->setNameProduit($req->get('nom'));
        $stock->setArtiste($req->get('art'));
        $stock->setAddres($req->get('adress'));
        $b = $req->get('dat');
        $birthdayDate = \DateTimeImmutable::createFromFormat('Y-m-d', $b);
        $stock->setDateEntr($birthdayDate);

        $stock->setUserName($req->get('usr'));



        $em->persist($stock);
        $em->flush();

        $jsonContent = $Normalizer->normalize($stock, 'json', ['groups' => 'stock']);
        return new Response(json_encode($jsonContent));
    }
    #[Route("/addret/new", name: "addret")]
    public function addret(Request $req,   NormalizerInterface $Normalizer,CommandeRepository $commandeRepository)
    {
        $req->get('idcom');
        $idcomm = $commandeRepository->find($req->get('idcom'));

        $em = $this->getDoctrine()->getManager();
        $retour = new Retour();
        $retour->setIdCommende($idcomm);
        $retour->setNameProduit($req->get('nom'));
        $retour->setArtiste($req->get('art'));
        $retour->setAddres($req->get('adress'));
        $b = $req->get('dat');
        $birthdayDate = \DateTimeImmutable::createFromFormat('Y-m-d', $b);
        $retour->setDate($birthdayDate);

        $retour->setUserName($req->get('usr'));



        $em->persist($retour);
        $em->flush();

        $jsonContent = $Normalizer->normalize($retour, 'json', ['groups' => 'retour']);
        return new Response(json_encode($jsonContent));
    }
    #[Route("/updatecom/{id}", name: "updatecommande")]
    public function updatecomm(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->find($id);

        $commande->setIdPanier($req->get('panier')??$commande->getIdPanier());
        $commande->setPrenom($req->get('prenom')??$commande->getPrenom()) ;
        $commande->setNom($req->get('nom')??$commande->getNom());
        $commande->setNumero($req->get('num')??$commande->getNumero());
        $commande->setStatus($req->get('status')??$commande->getStatus());
        $commande->setTotalAmount($req->get('amount')??$commande->getTotalAmount());
        $commande->setCodepostal($req->get('code')??$commande->getCodepostal());
        $commande->setAdresse($req->get('adresse')??$commande->getAdresse());
        $commande->setStatuLiv($req->get('statu_liv')??$commande->getStatus());
        $dateString = '2023-04-29 12:00:00';
        $format = 'Y-m-d H:i:s';
        $dateTime = \DateTimeImmutable::createFromFormat($format, $dateString);

        $commande->setCreatedAt($dateTime??$commande->getCreatedAt());
        $em->persist($commande);
        $em->flush();

        $jsonContent = $Normalizer->normalize($commande, 'json', ['groups' => 'commande']);
        return new Response("commande updated successfully " . json_encode($jsonContent));
    }
    #[Route("/updateliv/{id}", name: "updatelivraison")]
    public function updateliv(Request $req, $id, NormalizerInterface $Normalizer,CommandeRepository $commandeRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $livraison = $em->getRepository(Livraison::class)->find($id);
        $req->get('idcom');
        $idcomm = $commandeRepository->find($req->get('idcom')??$livraison->getIdCommende());
        $em = $this->getDoctrine()->getManager();
        $livraison->setIdCommende($idcomm);
        $livraison->setNameProduit($req->get('nom')??$livraison->getNameProduit());
        $livraison->setArtiste($req->get('art')??$livraison->getArtiste());
        $livraison->setAddres($req->get('adress')??$livraison->getAddres());
        $b = $req->get('dat');
        $birthdayDate = \DateTimeImmutable::createFromFormat('Y-m-d', $b);
        $livraison->setDateSort($birthdayDate );
        $livraison->setUserName($req->get('usr')??$livraison->getUserName());
        $em->persist($livraison);
        $em->flush();

        $jsonContent = $Normalizer->normalize($livraison, 'json', ['groups' => 'livraison']);
        return new Response("livraison updated successfully " . json_encode($jsonContent));

    }
    #[Route("/updatesto/{id}", name: "updatestock")]
    public function updatestk(Request $req, $id, NormalizerInterface $Normalizer,CommandeRepository $commandeRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository(Stock::class)->find($id);
        $req->get('idcom');
        $idcomm = $commandeRepository->find($req->get('idcom')??$stock->getIdCommende());
        $em = $this->getDoctrine()->getManager();
        $stock->setIdCommende($idcomm);
        $stock->setNameProduit($req->get('nom')??$stock->getNameProduit());
        $stock->setArtiste($req->get('art')??$stock->getArtiste());
        $stock->setAddres($req->get('adress')??$stock->getAddres());
        $b = $req->get('dat');
        $birthdayDate = \DateTimeImmutable::createFromFormat('Y-m-d', $b);
        $stock->setDateEntr($birthdayDate );
        $stock->setUserName($req->get('usr')??$stock->getUserName());
        $em->persist($stock);
        $em->flush();

        $jsonContent = $Normalizer->normalize($stock, 'json', ['groups' => 'stock']);
        return new Response("stock updated successfully " . json_encode($jsonContent));

    }
    #[Route("/updatret/{id}", name: "updateretour")]
    public function updaterte(Request $req, $id, NormalizerInterface $Normalizer,CommandeRepository $commandeRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $retour = $em->getRepository(Retour::class)->find($id);
        $req->get('idcom');
        $idcomm = $commandeRepository->find($req->get('idcom')??$retour->getIdCommende());
        $em = $this->getDoctrine()->getManager();
        $retour->setIdCommende($idcomm);
        $retour->setNameProduit($req->get('nom')??$retour->getNameProduit());
        $retour->setArtiste($req->get('art')??$retour->getArtiste());
        $retour->setAddres($req->get('adress')??$retour->getAddres());
        $b = $req->get('dat');
        $birthdayDate = \DateTimeImmutable::createFromFormat('Y-m-d', $b);
        $retour->setDate($birthdayDate );
        $retour->setUserName($req->get('usr')??$retour->getUserName());
        $em->persist($retour);
        $em->flush();

        $jsonContent = $Normalizer->normalize($retour, 'json', ['groups' => 'retour']);
        return new Response("retour updated successfully " . json_encode($jsonContent));

    }
}