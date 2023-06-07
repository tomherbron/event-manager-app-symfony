<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function Symfony\Component\Translation\t;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UtilisateurRepository $repository): Response
    {
//         if ($this->getUser()) {
//            return $this->redirectToRoute('sortie_list');
//         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(AuthenticationUtils $authenticationUtils): Response
    {
        $this->get('security.token_storage')->setToken(null);
        $this->get('request_stack')->getCurrentRequest()->getSession()->invalidate();

        $message = "Votre compte a été désactivé.";

        // Redirect to the desired page after logout
        return $this->redirectToRoute('app_home', ['message' => $message]);

    }
}
