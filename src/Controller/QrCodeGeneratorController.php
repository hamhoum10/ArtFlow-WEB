<?php

namespace App\Controller;

use App\Entity\Enchere;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;


class QrCodeGeneratorController extends AbstractController
{
    #[Route('/qr-codes/{ide}', name: 'app_qr_codes')]
    public function generateQrCode(Enchere $enchere): Response
    {

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

        return $this->render('qr_code_generator/index.html.twig', $qrCodes);
    }
}