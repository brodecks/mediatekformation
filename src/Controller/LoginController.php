<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur de gestion de l'authentification
 */
class LoginController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion et gère les erreurs d'authentification
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error
        ]);
    }
    
    /**
     * Gère la déconnexion de l'utilisateur (interceptée par le pare-feu Symfony)
     */
    #[Route('/logout', name: 'logout')]
    public function logout(){
        //commentaire pour remplir la methode et supprimer l'erreur sonarlint
    }
}