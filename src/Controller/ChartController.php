<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Retour;
use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
#[Route('/chart')]
class ChartController extends AbstractController
{public function __construct(EntityManagerInterface $entityManager)
{
    $this->entityManager = $entityManager;
}
    public function index1()
    {
        $tab = array();
        // Get the count of all Livraison entities
        $count1 = $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(l)')
            ->from(Livraison::class, 'l')
            ->getQuery()
            ->getSingleScalarResult();


        $count2 = $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(l)')
            ->from(Commande::class, 'l')
            ->getQuery()
            ->getSingleScalarResult();
        $count3 = $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(l)')
            ->from(Stock::class, 'l')
            ->getQuery()
            ->getSingleScalarResult();
        $count4 = $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(l)')
            ->from(Retour::class, 'l')
            ->getQuery()
            ->getSingleScalarResult();
        $count5 = $this -> entityManager
            ->createQueryBuilder()
            ->select( "t.total_amount ")
            ->from(Commande::class,"t")
//            ->groupBy('month')
//            ->orderBy('month')
            ->getQuery()
            ->getScalarResult();



        $tab[0] = $count1;
        $tab[1] = $count2;
        $tab[2]= $count3;
        $tab[3] = $count4;
        $tab[4]=$count5;
//        $request = Request::create('/query', 'GET', array('tab' => $tab));
//        $response = $this->container->get('http_kernel')->handle($request);



        return $tab;

    }

    public function chartAction()
    { $count =$this->index1() ;




        $data = array(
            'labels' => ["Livraison", "Commande", "Stock", "April", "Retour"],
            'datasets' => [
                [
                    'label' => ['chart graph'],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FFA500'],
                    'borderColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FFA500'],
                    'borderWidth' => 5,
                    'data' => [$count[0], $count[1], $count[2], $count[3]],

                ],
            ],

        );
        $data1 = array(
            'labels' => ["jan", "feb", "mar", "April", "mai","jun","jul","aug","sep","oct","nov","des"],
            'datasets' => [
                [
                    'label' => "total per mon",
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FFA500'],
                    'borderColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FFA500'],
                    'borderWidth' => 5,
                    'data' => [$count[4]],
                ],
            ],

        );

        return $this->render('chart/index.html.twig',['data'=>$data,'countliv'=>$count[0],'countcom'=>$count[1],'countsto'=>$count[2],'countre'=>$count[3],]);

    }

}