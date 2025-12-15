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
    $correo = $authenticationUtils->getLastUsername();
    $error = $authenticationUtils->getLastAuthenticationError();

    $usuario = $this->getUser();

        if ($usuario) {
            return $this->redirectToRoute('app_pagina_princiapal');
        } else {
        return $this->render('security/iniciarSesion.html.twig', [
        'correo' => $correo,
        'error' => $error,
        ]);
        }

}

    #[Route('/salir', name: 'app_salir')]
    public function logout(): void
    {
        // Este método puede estar vacío: Symfony se encarga de la lógica de cierre de sesión.
    }
}