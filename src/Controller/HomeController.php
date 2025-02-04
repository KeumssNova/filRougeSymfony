<?php

namespace App\Controller;

use App\Repository\ProjetsRepository;
use App\Repository\TachesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjetsRepository $projetsRepository,TachesRepository $tachesRepository, RequestStack $stack): Response
    {   
        $nombreProjets = $projetsRepository->count([]);
        $nombreTaches = $tachesRepository->count([]);

        $stack->getSession()->set('team',[
            [
                'id' => 1001,
                'matricule'=> 'ABC'
            ], 
            [
                'id' => 1002,
                'matricule'=> 'QWERTY'
            ]
        ]);
        // $team = $stack->getSession()->get('team');
        // dd($team);
        return $this->render('home/home.html.twig', [
            'nombreProjets' =>$nombreProjets,
            'nombreTaches' => $nombreTaches
        ]);
    }
}
