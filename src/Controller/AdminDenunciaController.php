<?php

namespace App\Controller;

use App\Repository\DenunciaComentarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/denuncias')]
class AdminDenunciaController extends AbstractController
{
    #[Route('/', name: 'admin_denuncias')]
    public function index(DenunciaComentarioRepository $repo): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $denuncias = $repo->findBy([], ['fecha' => 'DESC']);

        return $this->render('admin/denuncias.html.twig', [
            'denuncias' => $denuncias
        ]);
    }

    #[Route('/{id}/estado/{estado}', name: 'admin_denuncia_estado')]
    public function cambiarEstado(int $id,string $estado,DenunciaComentarioRepository $repo,EntityManagerInterface $em): Response {
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

        $denuncia->setEstado($estado);
        $em->flush();

        $this->addFlash('success', 'Denuncia actualizada correctamente');
        return $this->redirectToRoute('admin_denuncias');
    }
}