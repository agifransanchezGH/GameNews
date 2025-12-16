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

// Controlador para creación de comentarios y gestión de votos sobre comentarios.
class ComentarioController extends AbstractController 
{
// Crea un nuevo comentario asociado a una noticia.
#[Route('/noticia/{id}/comentario', name: 'agregar_comentario', methods: ['POST'])]
public function agregarComentario(Request $request, EntityManagerInterface $em, int $id): Response
{
    $noticia = $em->getRepository(Noticia::class)->find($id);
    if (!$noticia) {
        throw $this->createNotFoundException('Noticia no encontrada');
    }

    //Contenido del comentario que se quiere realizar
    $contenido = $request->request->get('contenido');

    //Comprueba que el contenido no este vacio y haya un usuario autenticado
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

// Registra o actualiza el voto de un usuario sobre un comentario concreto.
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
    //(int) convierte el valor obtenido a entero 
    $valor = (int) $request->request->get('valor'); // "1" o "0"
    //Se compara si el entero es igual a uno y se almacena el resultado (true o false)
    $valor = $valor === 1;

    $votoExistente = $em->getRepository(VotoComentario::class)->findOneBy(['usuario' => $usuario, 'comentario' => $comentario]);

    //Se verifica si existe el voto
    if ($votoExistente) {
        $votoExistente->setValor($valor);//Se setea el nuevo valor
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