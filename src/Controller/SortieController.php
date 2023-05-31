<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sorties', name: 'sortie_')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie')]
    public function index(): Response
    {
        return $this->render('sortie/add.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }
}
