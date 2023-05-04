<?php

namespace App\Controller;
use App\Entity\Client;
use App\Entity\Enchere;
use App\Entity\Participant;
use App\Repository\EnchereRepository;
use App\Repository\ParticipantRepository;
use App\Controller\EnchereController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

class PdfGeneratorController extends AbstractController
{

    #[Route('/pdf/generator/{ide}', name: 'pdf_generator')]
    public function index(int $ide): Response
    {

        $logoPath = $this->getParameter('kernel.project_dir') . '/public/uploads/logo.png';
        $logoData = base64_encode(file_get_contents($logoPath));

         $addPath = $this->getParameter('kernel.project_dir') . '/public/uploads/PDFPICC.png';
         $addData = base64_encode(file_get_contents($addPath));
        $entityManager = $this->getDoctrine()->getManager();
        $enchere = $entityManager->getRepository(Enchere::class)->find($ide);
        if (!$enchere) {
            throw $this->createNotFoundException('No enchere found for id '.$ide);
        }

        // Get the participant with the maximum montant for this enchere
        $participant = $entityManager->getRepository(Participant::class)->findOneBy(['ide' => $enchere], ['montant' => 'DESC']);

        // If there is a participant with a maximum montant, get their client information
        if ($participant) {
            $client = $participant->getId();
        } else {
            $client = null;
        }

        $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $enchere->getImage();
        $imageData = base64_encode(file_get_contents($imagePath)); // convert image to base64 string

        $data = [
        'addressSrc'=>$addData,
            'logoSrc'=>$logoData,
            'imageSrc' => $imageData,
            'enchere' => $enchere,
            'client' => $client,
        ];

        $html =  $this->renderView('pdf_generator/index.html.twig', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response (
            $dompdf->stream('Winner.pdf', ["Attachment" => false]),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="resume.pdf"',
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'public',
                'Content-Length' => strlen($dompdf->output()),
                'Content-Transfer-Encoding' => 'binary',
            ]
        );
    }
}