<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Entity\Participant;
use App\Form\EnchereType;
use App\Controller\QrCodeGeneratorController;
use App\Form\ParticipantType;
use App\Repository\EnchereRepository;
use App\Repository\ParticipantRepository;
use Doctrine\Persistence\ManagerRegistry;
use ContainerJsZKEGQ\getManagerRegistryAwareConnectionProviderService;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Component\Routing\Generator;
use DateTime;
//use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('/enchere')]
class EnchereController extends AbstractController
{





    #[Route('/new', name: 'app_enchere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EnchereRepository $enchereRepository): Response
    {
        $enchere = new Enchere();
        $form = $this->createForm(EnchereType::class, $enchere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('Image')->getData();
            if($image!=null){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $enchere->setImage($fichier);}
            $enchereRepository->save($enchere, true);

            return $this->redirectToRoute('app_enchere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enchere/new.html.twig', [
            'img'=>"",
            'enchere' => $enchere,
            'form' => $form,
        ]);
    }













    #[Route('/index', name: 'app_enchere_index', methods: ['GET'])]
    public function index(EnchereRepository $enchereRepository): Response
    {
        return $this->render('enchere/index.html.twig', [

            'encheres' => $enchereRepository->findAll(),
        ]);
    }



        //welcome page of the website
    // + the FullCalendar
    #[Route('/welcomepage', name: 'app_welcomepage')]
    public function welcomepage(EnchereRepository $enchereRepository): Response
    {  $encheres = $enchereRepository->findAll();
        $images = [];

        foreach ($encheres as $enchere) {
            $images[] = $this->getParameter('images_directory') . '/' . $enchere->getImage();
        }

        return $this->render('enchere/frontindex.html.twig', [

            'encheres' => $encheres,
            'images' => $images,

        ]);
    }

    #[Route('/calendar', name: 'app_enchere_cal')]
    public function calendar(EnchereRepository $enchereRepository): Response
    {
        $encheres = $enchereRepository->findAll();

        // array of events for the FullCalendar

        foreach ($encheres as $enchere) {
            $events[] = [
                'title' => $enchere->getTitre(),
                'description' => $enchere->getDescription(),
                'start' => $enchere->getDateLimite(),
                'color' => '#257e4a',
                'price' => $enchere->getPrixdepart(),

            ];
        }
        return $this->render('enchere/fullcalendar.html.twig', [
            'events' => $events
        ]);
    }






        //this is where there's all the details about an auction
        //i have the qrcode and the form to add a participation in the database
        #[Route('/{ide}', name: 'app_enchere_showfront', methods: ['GET', 'POST'])]
        public function frontshow(Enchere $enchere, ParticipantRepository $participantRepository, Participant $participant, Request $request): Response
        {
            //$now for the date pdf download condition
            $now = new DateTime();

            $writer = new PngWriter();
            $qrCode = QrCode::create('https://www.binaryboxtuts.com/')
                ->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                ->setSize(120)
                ->setMargin(0)
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));
            $logo = Logo::create('img/logo.png')
                ->setResizeToWidth(60);
            $label = Label::create('')->setFont(new NotoSans(8));

            // Add Enchere information to the QR code
            $qrCodeText = "Enchere: " . $enchere->getIde() . "\n";
            $qrCodeText .= "Titre: " . $enchere->getTitre() . "\n";
            $qrCodeText .= "Description: " . $enchere->getDescription() . "\n";
            $qrCodeText .= "Prix départ: " . $enchere->getPrixdepart() . "\n";
            $qrCodeText .= "Date limite: " . $enchere->getDateLimite()->format('Y-m-d') . "\n";
            $qrCode = $qrCode->setData($qrCodeText);

            $qrCodes = [];
            $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
            $qrCodes['simple'] = $writer->write(
                $qrCode,
                null,
                $label->setText('Simple')
            )->getDataUri();

            //add the form for participation

            $newParticipant = new Participant();
            $newParticipantForm = $this->createForm(ParticipantType::class, $newParticipant);
            $newParticipantForm->handleRequest($request);

            if ($newParticipantForm->isSubmitted() && $newParticipantForm->isValid()) {
                $encheresParticipants = $participantRepository->findBy(['ide' => $newParticipant->getIde()]);
                //check montant > to the previous max(montant)
                $maxMontant = 0;

                foreach ($encheresParticipants as $encheresParticipant) {
                    $montant = $encheresParticipant->getMontant();
                    if ($montant > $maxMontant) {
                        $maxMontant = $montant;

                    }
                }

                if (($newParticipant->getMontant() - $maxMontant) < 100) {
                    $this->addFlash('error', sprintf('The bid must be at least 100 DT higher than the current highest bid (%s DT).', $maxMontant));
                }else {
                    $participantRepository->save($newParticipant, true);

                    return $this->redirectToRoute('app_welcomepage', [], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->render('enchere/frontshow.html.twig', [
                'participants' => $participantRepository->findAll(),
                'enchere' => $enchere,
                'participant' => $participant,
                'newParticipantForm' => $newParticipantForm->createView(),
                //  'qrCodeDataUri' => $qrCodeDataUri, // pass the QR code data URI to the view
                'qrCodes' => $qrCodes,
                'now' => $now,

            ]);
        }





    /*
       ---------------- THIS IS OLD FRONTSHOW
    #[Route('/{ide}', name: 'app_enchere_showfront', methods: ['GET'])]
    public function frontshow( Enchere $enchere,ParticipantRepository $participantRepository, Participant $participant): Response
    {
        return $this->render('enchere/frontshow.html.twig', [
            'participants' => $participantRepository->findAll(),
            'enchere' => $enchere,
            'participant' => $participant,

        ]);

    }

*/

    #[Route('/{ide}/delete', name: 'app_enchere_delete')]
    public function delete(Request $request, EnchereRepository $enchereRepository,$ide,ManagerRegistry $doctrine): Response
    {
        $enchere= $enchereRepository->find($ide);
        $em =$doctrine->getManager();
        $em->remove($enchere);
        $em->flush();

        return $this->redirectToRoute('app_enchere_index');
    }




















    #[Route('/show', name: 'app_enchere_show', methods: ['GET'])]
    public function show(Enchere $enchere): Response
    {
        return $this->render('enchere/show.html.twig', [
            'enchere' => $enchere,
        ]);
    }

    #[Route('/edit/{ide}', name: 'app_enchere_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enchere $enchere, EnchereRepository $enchereRepository): Response
    {
        $form = $this->createForm(EnchereType::class, $enchere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('Image')->getData();
            if($image!=null){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $enchere->setImage($fichier);}

            $enchereRepository->save($enchere, true);
            $this->addFlash('message','successfully!');

            return $this->redirectToRoute('app_enchere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enchere/edit.html.twig', [
            'img'=>$enchere->getImage(),
            'enchere' => $enchere,
            'form' => $form,
        ]);
    }



}



