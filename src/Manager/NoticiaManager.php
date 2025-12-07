<?php

namespace App\Manager;

use App\Entity\Comentario;
use App\Repository\NoticiaRepository;
use App\Entity\Noticia;
use Doctrine\ORM\EntityManagerInterface;

class NoticiaManager
{
    private NoticiaRepository $noticiaRepository;
    private EntityManagerInterface $em;

    public function __construct(NoticiaRepository $noticiaRepository, EntityManagerInterface $em)
    {
        $this->noticiaRepository = $noticiaRepository;
        $this->em = $em;
    }

    public function getNoticias(): array
    {
        return $this->noticiaRepository->findAll();
    }

    public function getNoticia(int $id): ?Noticia
    {
        return $this->noticiaRepository->find($id);
    }

    public function getUltimasNoticias(int $cantidad = 3): array
    {
        return $this->noticiaRepository->findBy([], ['fechaPublicacion' => 'DESC'], $cantidad);
    }

    public function crearNoticia(Noticia $noticia): void
    {
        $this->em->persist($noticia);
        $this->em->flush();
    }


    public function actualizarNoticia(Noticia $noticia): void
    {
        $this->em->flush(); // Doctrine detecta cambios automáticamente
    }


    public function eliminarNoticia(Noticia $noticia): void
    {
        $this->em->remove($noticia);
        $this->em->flush();
    }

    public function agregarComentario(Comentario $comentario, Noticia $noticia): void
    {
        $noticia->addComentario($comentario);
        $this->em->persist($comentario);
        $this->em->flush();
    }
}