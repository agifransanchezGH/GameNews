<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Usuario;

use function PHPUnit\Framework\at;

class RegistrarseController extends AbstractController
{
    #[Route('/registrarse', name: 'app_registrarse', methods: ['GET', 'POST'])]
    public function registrarse(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $nombreUsuario = $request->request->get('nombreUsuario');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirmPassword');
            $terminosCheck = $request->request->get('aceptoTerminos');

            if (!$terminosCheck) {
                $this->addFlash('error', 'Es obligatorio que acepte los términos y condiciones');
                return $this->redirectToRoute('app_registrarse');
            } else {
                // Verificación de que los campos no esten vacios
                if (empty($email) || empty($nombreUsuario) || empty($password) || empty($confirmPassword)) {
                    $this->addFlash('error', 'Todos los campos son obligatorios');
                    return $this->redirectToRoute('app_registrarse');
                }
                // Verificación de si ambas contraseñas coinciden
                if ($password !== $confirmPassword) {
                    $this->addFlash('error', 'Las contraseñas no coinciden');
                    return $this->redirectToRoute('app_registrarse');
                }

                // Verificación de que la contraseña cumpla con los requisitos
                if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/', $password)) {
                    $this->addFlash('error', 'La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y números');
                    return $this->redirectToRoute('app_registrarse');
                }

                // Verificación si el correo ya está registrado
                $usuarioExistente = $em->getRepository(Usuario::class)->findOneBy(['correo' => $email]);
                if ($usuarioExistente) {
                    $this->addFlash('error', 'El correo ya está registrado');
                    return $this->redirectToRoute('app_registrarse');
                }

                // Creación de nuevo usuario
                $usuario = new Usuario();
                $usuario->setCorreo($email);
                $usuario->setNombreUsuario($nombreUsuario);
                $usuario->setContraseña($passwordHasher->hashPassword($usuario, $password));
                $usuario->setRol('ROLE_USER');
                $usuario->setEstado(true);

                $em->persist($usuario);
                $em->flush();

                $this->addFlash('success', 'Registro exitoso');
                return $this->redirectToRoute('app_pagina_principal');
            }
        }

        return $this->render('security/registrarse.html.twig');
    }

    #[Route('/registrarse/normas', name: 'app_normas')]
    public function irANormas(): Response
    {
        return $this->render('security/normas.html.twig');
    }
}
