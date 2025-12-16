<?php

namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Controlador para la gestión de usuarios desde el panel de administración.
class UsuarioController extends AbstractController
{
    // Lista todos los usuarios para que el administrador pueda gestionarlos.
    #[Route('/admin/usuarios', name: 'usuarios_listar')]
    public function listar(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $usuarios = $em->getRepository(Usuario::class)->findAll();

        return $this->render('admin/usuarios.html.twig', [
            'usuarios' => $usuarios,
        ]);
    }

    // Renderiza el formulario para editar los datos y el estado de un usuario.
    #[Route('/admin/usuario/{id}/editar', name: 'usuario_editar')]
    public function editar(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $usuario = $em->getRepository(Usuario::class)->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/editarUsuarios.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    // Actualiza el rol y el estado (activo/suspendido) de un usuario.
    #[Route('/admin/usuario/{id}/actualizar', name: 'usuario_actualizar', methods: ['POST'])]
    public function actualizar(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $usuario = $em->getRepository(Usuario::class)->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }
        // Interpretar estado (radio buttons con value "1" o "0")
        $estado = $request->request->get('activo');
        $activo = $estado === "1";

        $usuario->setRol($request->request->get('rol'));
        $usuario->setEstado($activo);

        $em->flush();

        $this->addFlash('success', 'Usuario actualizado correctamente');
        return $this->redirectToRoute('usuarios_listar');
    }



    // Elimina un usuario, respetando la regla de negocio de no borrar al administrador principal.
    #[Route('/admin/usuario/{id}/eliminar', name: 'usuario_eliminar', methods: ['POST'])]
    public function eliminar(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $usuario = $em->getRepository(Usuario::class)->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        // RN1: impedir eliminar admin principal
        if ($usuario->getRol() === 'ROLE_ADMIN' && $usuario->getCorreo() === 'admin@gamenews.com') {
            $this->addFlash('error', 'No se puede eliminar un administrador principal');
            return $this->redirectToRoute('usuarios_listar');
        }

        $em->remove($usuario);
        $em->flush();

        $this->addFlash('success', 'Usuario eliminado correctamente');
        return $this->redirectToRoute('usuarios_listar');
    }
}
