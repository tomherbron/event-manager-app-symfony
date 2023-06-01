<?php

namespace App\Controller;

use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Tools\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function update(int                         $id,
                           Request                     $request,
                           UtilisateurRepository       $utilisateurRepository,
                           Uploader                    $uploader,
                           UserPasswordHasherInterface $encoder): Response
    {

        $utilisateur = $utilisateurRepository->find($id);
        $utilisateurForm = $this->createForm(UtilisateurType::class, $utilisateur);

        $utilisateurForm->handleRequest($request);
        $this->encoder = $encoder;

        if ($utilisateurForm->isSubmitted() && $utilisateurForm->isValid()) {

            $utilisateur->setActif(true);
            $plainPassword = $utilisateur->getPassword();
            $encoded = $this->encoder->hashPassword($utilisateur, $plainPassword);
            $utilisateur->setPassword($encoded);

            /**
             * @var UploadedFile $file
             */

            $file = $utilisateurForm->get('photo')->getData();
            if ($file) {
                $newFileName = $uploader->save($file, $utilisateur->getUsername() . '-' . $utilisateur->getNom(), $this->getParameter('upload_profile_picture'));
                $utilisateur->setPhoto($newFileName);
            }

            $utilisateurRepository->save($utilisateur, true);
            return $this->redirectToRoute('utilisateur_show',
                ['id' => $utilisateur->getId()]);
        }

        return $this->render('utilisateur/update.html.twig', [
            'utilisateur' => $utilisateur,
            'utilisateurForm' => $utilisateurForm->createView()
        ]);
    }

}
