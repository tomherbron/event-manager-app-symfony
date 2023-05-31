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
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/sortie', name: 'sortie_')]
class SortieController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(Request $request, SortieRepository $repository): Response
    {
        $newSortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $newSortie);

        $user  = $this->getUser();

        $sortieForm->handleRequest($request);
        if ($sortieForm->isSubmitted()){

            $newSortie->setOrganisateur($user);
            $campus = $sortieForm->get('campus')->getData();
            $newSortie->setCampus($campus);


            $repository->save($newSortie, true);
            return $this->redirectToRoute('main_home');
        }


        return $this->render('sortie/add.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }
}
