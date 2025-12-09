<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Noticia;
use App\Entity\Comentario;


class ComentarioController extends AbstractController 
{
#[Route('/noticia/{id}/comentario', name: 'agregar_comentario', methods: ['POST'])]
public function agregarComentario(Request $request, EntityManagerInterface $em, int $id): Response
{
    $noticia = $em->getRepository(Noticia::class)->find($id);
    if (!$noticia) {
        throw $this->createNotFoundException('Noticia no encontrada');
    }

    $contenido = $request->request->get('contenido');

    if ($contenido && $this->getUser()) {
        $comentario = new Comentario();
        $comentario->setContenido($contenido);
        $comentario->setUsuario($this->getUser());
        $comentario->setNoticia($noticia);
        $comentario->setFechaHora(new \DateTime());
        $comentario->setEstadoModeracion('normal');
        $em->persist($comentario);
        $em->flush();
    }

    return $this->redirectToRoute('app_pagina_noticia', ['id' => $noticia->getId()]);
}
}