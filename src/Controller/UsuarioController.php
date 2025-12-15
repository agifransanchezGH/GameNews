<?php

namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    #[Route('/admin/usuarios', name: 'usuarios_listar')]
    public function listar(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $usuarios = $em->getRepository(Usuario::class)->findAll();

        return $this->render('admin/usuarios.html.twig', [
            'usuarios' => $usuarios,
        ]);
    }

    //Metodo que renderiza la template para editar un usuario
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
    //Metodo que actualiza la informacion/estdo del usuario
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
