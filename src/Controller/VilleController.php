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
            return $this->redirectToRoute('ville_add');
        }

        $villes = $villeRepository->findAll();


        return $this->render('ville/add.html.twig', [
            'villeForm' => $villeForm->createView(),
            'villes'=>$villes
        ]);
    }

#[Route('/update/{id}', name: 'update', requirements: ["id" => "\d+"])]
public function edit(Request $request, int $id,
                     VilleRepository $villeRepository): Response{
        $ville = $villeRepository->find($id);
        $villeForm = $this->createForm(VilleType::class,$ville);

        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){
            $villeRepository->save($ville,true);
            $this->addFlash('success','Ville modifiée avec succès');
            return $this->redirectToRoute('ville_add');
        }

        return $this->render('ville/update.html.twig',[
            'villeForm'=>$villeForm->createView()
        ]);
}

#[Route('/delete/{id}', name : 'delete', requirements: ["id" => "\d+"])]
public function delete(Request $request, int $id,
                       VilleRepository $villeRepository): Response{
        $ville = $villeRepository->find($id);

        $villeRepository->remove($ville, true);

        $this -> addFlash('success', 'Ville supprimée');

        return  $this->redirectToRoute('ville_add');
}


}
