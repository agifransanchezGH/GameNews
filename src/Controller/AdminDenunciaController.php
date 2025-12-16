<?php

namespace App\Controller;

use App\Repository\DenunciaComentarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Controlador que permite al administrador revisar y cambiar el estado de las denuncias.
#[Route('/admin/denuncias')]
class AdminDenunciaController extends AbstractController
{
    // Lista todas las denuncias ordenadas por fecha para su revisión.
    #[Route('/', name: 'admin_denuncias')]
    public function index(DenunciaComentarioRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Se buscan las noticias en orden descendente (mas recienete a mas antigua)
        $denuncias = $repo->findBy([], ['fecha' => 'DESC']);
        $comentarios = $repo->findAll();

        return $this->render('admin/denuncias.html.twig', [
            'denuncias' => $denuncias,
            'comentarios' => $comentarios,
        ]);
    }

    // Cambia el estado de una denuncia (resuelta o rechazada).
    // Si se marca como resuelta, también modifica el estado de moderación del comentario asociado.
    #[Route('/{id}/estado/{estado}', name: 'admin_denuncia_estado')]
    public function cambiarEstado(int $id, string $estado, DenunciaComentarioRepository $repo, EntityManagerInterface $em): Response
    {
        // Validación del rol
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $denuncia = $repo->find($id);
        if (!$denuncia) {
            $this->addFlash('error', 'Denuncia no encontrada');
            return $this->redirectToRoute('admin_denuncias');
        }

        if (!in_array($estado, ['resuelta', 'rechazada'])) {
            $this->addFlash('error', 'Estado inválido');
            return $this->redirectToRoute('admin_denuncias');
        }

        // Si la denuncia se resuelve, marcar el comentario asociado como Borrado
        if ($estado === 'resuelta') {
            $comentario = $denuncia->getComentario();
            if ($comentario) {
                $comentario->setEstadoModeracion('Borrado');
                $em->persist($comentario);
            }
        }

        $denuncia->setEstado($estado);
        $em->flush();

        $this->addFlash('success', 'Denuncia actualizada correctamente');
        return $this->redirectToRoute('admin_denuncias');
    }
}
