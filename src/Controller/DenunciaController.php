<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\DenunciaComentario;
use App\Repository\ComentarioRepository;
use App\Repository\DenunciaComentarioRepository;
use Doctrine\ORM\EntityManagerInterface;

class DenunciaController extends AbstractController {
#[Route('/comentario/{id}/denunciar', name: 'comentario_denunciar')]
public function denunciar(int $id, Request $request, ComentarioRepository $comentarioRepo, DenunciaComentarioRepository $denunciaRepo, EntityManagerInterface $em): Response 
{
    $usuario = $this->getUser();
    if (!$usuario) {
        $this->addFlash('error', 'Debes iniciar sesión para denunciar comentarios');
        return $this->redirectToRoute('app_iniciar_sesion');
    }

    $comentario = $comentarioRepo->find($id);
    if (!$comentario) {
        $this->addFlash('error', 'Comentario no encontrado');
        return $this->redirectToRoute('app_pagina_principal');
    }

    $yaReportado = $denunciaRepo->findOneBy(['denunciante' => $usuario,'comentario' => $comentario]);
    if ($yaReportado) {
        $this->addFlash('error', 'Ya has reportado este comentario');
        return $this->redirectToRoute('app_pagina_noticia', ['id' => $comentario->getNoticia()->getId()]);
    }

    if ($request->isMethod('POST')) {
        $tipo = $request->request->get('tipo');
        $descripcion = $request->request->get('descripcion');

        if (!$tipo) {
            $this->addFlash('error', 'Debes seleccionar un motivo para la denuncia');
            return $this->redirectToRoute('comentario_denunciar', ['id' => $id]);
        }

        $denuncia = new DenunciaComentario();
        $denuncia->setDenunciante($usuario);
        $denuncia->setComentario($comentario);
        $denuncia->setTipo($tipo);
        $denuncia->setDescripcion($descripcion);
        $denuncia->setEstado('pendiente');
        $denuncia->setFecha(new \DateTime());

        $em->persist($denuncia);
        $em->flush();

        $this->addFlash('success', 'Denuncia enviada correctamente');
        return $this->redirectToRoute('app_pagina_noticia', ['id' => $comentario->getNoticia()->getId()]);
    }

    return $this->render('denuncias/reportar.html.twig', [
        'comentario' => $comentario
    ]);
}
}
