<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
#[Route('/chart')]
class ChartController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function chartAction()
    {
        $data = array(
            'labels' => array("January", "February", "March", "April", "May", "June", "July"),
            'datasets' => array(
                array(
                    'label' => "My First dataset",
                    'backgroundColor' => "rgba(255,99,132,0.2)",
                    'borderColor' => "rgba(255,99,132,1)",
                    'borderWidth' => 1,
                    'data' => array(65, 59, 80, 81, 56, 55, 40),
                ),
            ),
        );
        return $this->render('chart/index.html.twig',['data'=>$data]);
    }
}