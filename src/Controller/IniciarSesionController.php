<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class IniciarSesionController extends AbstractController
{
    #[Route('/login', name: 'app_iniciar_sesion')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()->getEstado() === true){
            return $this->redirectToRoute('inicio');
        } else {
            $this->addFlash('error', 'Su cuenta ha sido suspendida. Contacte con el administrador para más información.');
            return $this->redirectToRoute('app_salir');
        }
        
        return $this->render('security/iniciarSesion.html.twig', []);
    }

    #[Route('/salir', name: 'app_salir')]
    public function logout(): void
    {
        // Este método puede estar vacío: Symfony se encarga de la lógica de cierre de sesión.
    }
}