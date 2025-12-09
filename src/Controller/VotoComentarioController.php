<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comentario;
use App\Entity\VotoComentario;

class VotoComentarioController extends AbstractController
{
    #[Route('/comentario/{id}/votar/{valor}', name: 'votar_comentario')]
public function votarComentario(int $id,bool $valor,EntityManagerInterface $em): Response 
{
    $usuario = $this->getUser();

    if (!$usuario) {
        return $this->redirectToRoute('app_iniciar_sesion');
    }

    $comentario = $em->getRepository(Comentario::class)->find($id);
    if (!$comentario) {
        throw $this->createNotFoundException('Comentario no encontrado');
    }

    $votoExistente = $em->getRepository(VotoComentario::class)
        ->findOneBy(['usuario' => $usuario, 'comentario' => $comentario]);

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