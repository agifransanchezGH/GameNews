<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DenunciaComentarioRepository;
use App\Repository\ComentarioRepository;
use App\Repository\NoticiaRepository;
use App\Repository\UsuarioRepository;
use App\Repository\VotoComentarioRepository;

// Controlador del panel principal del administrador: muestra estadísticas y accesos rápidos.
class AdminDashboardController extends AbstractController 
{
    // Muestra datos generales: denuncias pendientes, comentarios recientes, totales, etc.
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(DenunciaComentarioRepository $denunciaRepo,ComentarioRepository $comentarioRepo,NoticiaRepository $noticiaRepo,UsuarioRepository $usuarioRepo, VotoComentarioRepository $votoComentarioRepo): Response 
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Datos principales
        $denunciasPendientes = $denunciaRepo->findBy(['estado' => 'pendiente']);
        $comentariosRecientes = $comentarioRepo->findBy([], ['fechaHora' => 'DESC'], 10);
        $noticias = $noticiaRepo->findAll();
        $usuarios = $usuarioRepo->findAll();
        $votoComentarioPositivo = $votoComentarioRepo->findBy(['valor' => '1']);
        $votoComentarioNegativo = $votoComentarioRepo->findBy(['valor' => '0']);
        // Estadísticas simples
        $estadisticas = [
            'totalNoticias' => count($noticias),
            'totalComentarios' => $comentarioRepo->count([]),
            'totalUsuarios' => count($usuarios),
            'denunciasPendientes' => count($denunciasPendientes),
            'TotalDeVotosPositivosComentarios' => count($votoComentarioPositivo),
            'TotalDeVotosNegativosComentarios' => count($votoComentarioNegativo),
        ];


        return $this->render('admin/dashboard.html.twig', [
            'denunciasPendientes' => $denunciasPendientes,
            'comentariosRecientes' => $comentariosRecientes,
            'noticias' => $noticias,
            'usuarios' => $usuarios,
            'estadisticas' => $estadisticas,
        ]);
    }

    // Acceso rápido a la gestión de denuncias desde el panel de administrador.
    #[Route('/admin/denuncias', name: 'admin_denuncias')]
    public function gestionarDenuncias(DenunciaComentarioRepository $repo): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $denuncias = $repo->findBy(['estado' => 'pendiente']);
        return $this->render('admin/denuncias.html.twig', ['denuncias' => $denuncias]);
    }

    // Acceso rápido a la gestión de usuarios desde el panel de administrador.
    #[Route('/admin/usuarios', name: 'admin_usuarios')]
    public function gestionarUsuarios(UsuarioRepository $repo): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $usuarios = $repo->findAll();
        return $this->render('admin/usuarios.html.twig', ['usuarios' => $usuarios]);
    }
}