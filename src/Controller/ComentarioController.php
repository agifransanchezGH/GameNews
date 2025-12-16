<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Noticia;
use App\Entity\Comentario;
use App\Entity\VotoComentario;


class ComentarioController extends AbstractController 
{
#[Route('/noticia/{id}/comentario', name: 'agregar_comentario', methods: ['POST'])]
public function agregarComentario(Request $request, EntityManagerInterface $em, int $id): Response
{
    $noticia = $em->getRepository(Noticia::class)->find($id);
    if (!$noticia) {
        throw $this->createNotFoundException('Noticia no encontrada');
    }

    //Contenido del comentario que se quiere realizar
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

#[Route('/comentario/{id}/votar', name: 'votar_comentario', methods: ['GET','POST'])]
public function votarComentario(int $id, Request $request, EntityManagerInterface $em): Response
{
    $usuario = $this->getUser();
    if (!$usuario) {
        return $this->redirectToRoute('app_iniciar_sesion');
    }

    $comentario = $em->getRepository(Comentario::class)->find($id);
    if (!$comentario) {
        throw $this->createNotFoundException('Comentario no encontrado');
    }

    $valor = (int) $request->request->get('valor'); // "1" o "0"
    $valor = $valor === 1;

    $votoExistente = $em->getRepository(VotoComentario::class)->findOneBy(['usuario' => $usuario, 'comentario' => $comentario]);

    if ($votoExistente) {
        $votoExistente->setValor($valor);
    } else {
        $voto = new VotoComentario();
        $voto->setUsuario($usuario);
        $voto->setComentario($comentario);
        $voto->setValor($valor);
        $em->persist($voto);
    }

    $em->flush();

    return $this->redirectToRoute('app_pagina_noticia', [
        'id' => $comentario->getNoticia()->getId()
    ]);
}
}