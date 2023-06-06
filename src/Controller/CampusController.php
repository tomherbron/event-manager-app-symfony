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
    public function add(Request                $request,
                        CampusRepository       $campusRepository,
                        EntityManagerInterface $entityManager
    ): Response


    {

        $campus = new Campus();
        $campusForm = $this->createForm(CampusType::class, $campus);

        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();
            return $this->redirectToRoute('campus_add');

        }

        $mesCampus = $campusRepository->findAll();


        return $this->render('campus/add.html.twig', [
            'campusForm' => $campusForm->createView(),
            'campus' => $mesCampus
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ["id" => "\d+"])]
    public function edit(Request          $request, int $id,
                         CampusRepository $campusRepository): Response
    {

        $campus = $campusRepository->find($id);
        $campusForm = $this->createForm(CampusType::class, $campus);

        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $campusRepository->save($campus, true);
            $this->addFlash('success', 'Campus modifié avec succès.');
            return $this->redirectToRoute('campus_add');
        }
        return $this->render('campus/update.html.twig', [
            'campusForm' => $campusForm->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ["id" => "\d+"])]
    public function delete(Request $request, int $id, CampusRepository $campusRepository): Response
    {
        $campus = $campusRepository->find($id);

        $campusRepository->remove($campus, true);

        $this->addFlash('success', "Campus supprimé !");

        return $this->redirectToRoute('campus_add');

    }
}
