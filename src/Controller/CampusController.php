<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/campus', name: 'campus_')]
class CampusController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(Request          $request,
                        CampusRepository  $campusRepository,
                        EntityManagerInterface $entityManager
    ): Response


    {

        $campus = new Campus();
        $campusForm = $this->createForm(CampusType::class, $campus);

        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()){
            $entityManager->persist($campus);
            $entityManager->flush();
            return $this->redirectToRoute('sortie_list');

        }
        return $this->render('campus/add.html.twig', [
            'campusForm'=>$campusForm->createView(),
        ]);
    }
}
