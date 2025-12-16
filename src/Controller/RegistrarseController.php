<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Usuario;

// Controlador de registro de nuevos usuarios y acceso a las normas de la comunidad.
class RegistrarseController extends AbstractController
{
    // Maneja el proceso de registro, validando datos y creando el usuario.
    #[Route('/registrarse', name: 'app_registrarse', methods: ['GET', 'POST'])]
    public function registrarse(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {   
        // Se valida que el formulario se haya enviado antes de procesar datos. Si se quita, la plantilla es renderizada con errores aunque no se hayan completado los campos
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $nombreUsuario = $request->request->get('nombreUsuario');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirmPassword');

            // Verificación de que los campos no estén vacíos.
            if (empty($email) || empty($nombreUsuario) || empty($password) || empty($confirmPassword)) {
                $this->addFlash('error', 'Todos los campos son obligatorios');
                return $this->render('security/registrarse.html.twig', [
                    'email' => $email,
                    'nombreUsuario' => $nombreUsuario,
                ]);
            }
            // Verificación de si ambas contraseñas coinciden.
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Las contraseñas no coinciden');
                return $this->render('security/registrarse.html.twig', [
                    'email' => $email,
                    'nombreUsuario' => $nombreUsuario,
                ]);
            }


            // Verificación de que la contraseña cumpla con los requisitos mínimos de seguridad.
            if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/', $password)) {
                $this->addFlash('error', 'La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y números');
                return $this->render('security/registrarse.html.twig', [
                    'email' => $email,
                    'nombreUsuario' => $nombreUsuario,
                ]);
            }

            // Verificación de aceptación de las Normas de la Comunidad (checkbox obligatorio).
            $aceptoTerminos = $request->request->get('aceptoTerminos');
            if (!$aceptoTerminos || $aceptoTerminos !== '1') {
                $this->addFlash('error', 'Debes aceptar las Normas de la Comunidad para registrarte');
                return $this->render('security/registrarse.html.twig', [
                    'email' => $email,
                    'nombreUsuario' => $nombreUsuario,
                ]);
            }

            // Verificación de que el correo electrónico no esté ya registrado.
            $usuarioExistente = $em->getRepository(Usuario::class)->findOneBy(['correo' => $email]);
            if ($usuarioExistente) {
                $this->addFlash('error', 'El correo ya está registrado');
                return $this->render('security/registrarse.html.twig', [
                    'email' => $email,
                    'nombreUsuario' => $nombreUsuario,
                ]);
            }

            // Creación del nuevo usuario con rol por defecto ROLE_USER y estado activo(true).
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
        return $this->render('security/registrarse.html.twig');
    }

    // Muestra la página con las Normas de la Comunidad.
    #[Route('/registrarse/normas', name: 'app_normas')]
    public function irANormas(): Response
    {
        return $this->render('security/normas.html.twig');
    }
}
