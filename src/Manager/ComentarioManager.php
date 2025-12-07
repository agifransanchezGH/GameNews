<?php
namespace App\Manager;

use App\Entity\Comentario;
use Doctrine\ORM\EntityManagerInterface;

class ComentarioManager
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function crearComentario(string $contenido, string $fecha, $noticia): Comentario
    {
        $comentario = new Comentario();
        
        $comentario->setContenido($contenido);
        $comentario->setFechaHora($fecha);
        $comentario->setNoticia($noticia);

        $this->em->persist($comentario);
        $this->em->flush();

        return $comentario;
    }
}