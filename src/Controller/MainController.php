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
    public function home(UtilisateurRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);

        if (!$user->isActif()){
            $message = "Ce compte a été désactivé.";
            return $this->redirectToRoute('app_login', ['error' => $message]);
        }

        return $this->render('main/home.html.twig');
    }
}
