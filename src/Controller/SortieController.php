<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sorties', name: 'sortie_')]
class SortieController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(Request $request, SortieRepository $repository): Response
    {
        $newSortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $newSortie);

        $sortieForm->handleRequest($request);
        if ($sortieForm->isSubmitted()){
            $repository->save($newSortie, true);
            return $this->redirectToRoute('main_home');
        }


        return $this->render('sortie/add.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }
}
