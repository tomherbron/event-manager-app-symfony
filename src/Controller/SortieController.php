<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
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
    public function add(Request $request, SortieRepository $sortieRepository, EtatRepository $etatRepository): Response
    {
        $newSortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $newSortie);

        $user = $this->getUser();

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted()){

            $newSortie->setOrganisateur($user);
            $campus = $sortieForm->get('campus')->getData();
            $newSortie->setCampus($campus);
            $newSortie->setEtat(
                $etatRepository->find('1')
            );

            $sortieRepository->save($newSortie, true);
            $this->addFlash('success', 'Sortie créee avec succès.');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('sortie/add.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    #[Route('/list', name:'list')]
    public function list(SortieRepository $sortieRepository) : Response
    {

        $sortie = $sortieRepository->findBy([],["nom"=>"ASC"]);


        return $this->render('sortie/list.html.twig', [
            'sorties' => $sortie
        ]);

    }

    #[Route('/detail/{id}', name:'show', requirements: ["id"=> "\d+"])]
public function show(int $id, SortieRepository $sortieRepository) : Response
    {
        $sortie = $sortieRepository->find($id);

        if (!$sortie){
            throw $this->createNotFoundException("Pas de sortie trouvée !");
        }
        return $this->render('sortie/show.html.twig',['sortie'=>$sortie]);
    }


    #[Route('/update/{id}', name: 'update', requirements: ["id" => "\d+"])]
    public function edit(Request $request, int $id, SortieRepository $sortieRepository): Response
    {

        $sortie = $sortieRepository->find($id);
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted()) {
            $sortieRepository->save($sortie, true);
            $this->addFlash('success', 'Sortie modifiée avec succès.');
            return $this->redirectToRoute('main_home');
        };

        return $this->render('sortie/update.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);

    }

    #[Route('/delete/{id}', name: 'delete', requirements: ["id" => "\d+"])]
    public function delete(Request $request, int $id, SortieRepository $sortieRepository) : Response
    {

        $sortie = $sortieRepository->find($id);
        $sortieRepository->remove($sortie, true);

        $this->addFlash('success', 'Sortie supprimée avec succès.');
        return $this->redirectToRoute('main_home');

    }

    public function publish(Request $request, int $id, SortieRepository $sortieRepository, EtatRepository $etatRepository) : Response
    {
        $sortie = $sortieRepository->find($id);
        $sortie->setEtat($etatRepository->find('2'));
        $sortieRepository->save($sortie, true);

        $this->addFlash('success', 'Sortie publiée.');
        return $this->redirectToRoute('main_home');

    }

}
