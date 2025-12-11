<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DenunciaComentarioRepository;
use App\Repository\ComentarioRepository;
use App\Repository\NoticiaRepository;
use App\Repository\UsuarioRepository;

class AdminController extends AbstractController 
{
#[Route('/admin/dashboard', name: 'admin_dashboard')]
public function dashboard(DenunciaComentarioRepository $denunciaRepo,ComentarioRepository $comentarioRepo,NoticiaRepository $noticiaRepo,UsuarioRepository $usuarioRepo): Response 
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $denunciasPendientes = $denunciaRepo->findBy(['estado' => 'pendiente']);
    $comentariosRecientes = $comentarioRepo->findBy([], ['fechaHora' => 'DESC'], 10);
    $noticias = $noticiaRepo->findAll();
    $usuarios = $usuarioRepo->findAll();

    return $this->render('admin/dashboard.html.twig', [
        'denunciasPendientes' => $denunciasPendientes,
        'comentariosRecientes' => $comentariosRecientes,
        'noticias' => $noticias,
        'usuarios' => $usuarios,
    ]);
}

#[Route('/admin/denuncias', name: 'admin_denuncias')]
public function gestionarDenuncias(DenunciaComentarioRepository $repo): Response {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $denuncias = $repo->findBy(['estado' => 'pendiente']);
    return $this->render('admin/denuncias.html.twig', ['denuncias' => $denuncias]);
}

#[Route('/admin/usuarios', name: 'admin_usuarios')]
public function gestionarUsuarios(UsuarioRepository $repo): Response {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $usuarios = $repo->findAll();
    return $this->render('admin/usuarios.html.twig', ['usuarios' => $usuarios]);
}

}