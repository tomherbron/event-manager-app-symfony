<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(SecurityController $securityController, UtilisateurRepository $userRepository): Response
    {

        if ($this->getUser()){
            $username = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(['username' => $username]);

            if (!$user->isActif()){
                $message = "Votre compte a été désactivé.";
                return $this->redirectToRoute('app_logout');
            }
        }

        return $this->render('main/home.html.twig');
    }
}
