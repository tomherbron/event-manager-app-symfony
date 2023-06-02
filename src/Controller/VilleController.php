<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ville', name: 'ville_')]
class VilleController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(Request $request,
                        VilleRepository $villeRepository,
                        EntityManagerInterface $entityManager
    ): Response
    {
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);

        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm-> isValid()){


            $entityManager->persist($ville);
            $entityManager->flush();
            return $this->redirectToRoute('sortie_list');
        }


        return $this->render('ville/add.html.twig', [
            'villeForm' => $villeForm->createView()
        ]);
    }
}
