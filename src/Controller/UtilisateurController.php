<?php

namespace App\Controller;

use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateur', name: 'utilisateur_')]
class UtilisateurController extends AbstractController
{
    #[Route('/detail/{id}', name: 'show', requirements: ["id" => "\d+"])]
    public function show(int                   $id,
                         UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $utilisateurRepository->find($id);

        if (!$utilisateur) {
            throw $this->createNotFoundException("Utilisateur non trouvé !");
        }

        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ["id" => "\d+"])]
    public function update(int $id,
                           Request $request,
                           UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
        $utilisateurForm = $this->createForm(UtilisateurType::class, $utilisateur);

        return $this->render('utilisateur/update.html.twig', [
            'utilisateur'=> $utilisateur,
            'utilisateurForm' => $utilisateurForm->createView()
    ]);
    }
}
