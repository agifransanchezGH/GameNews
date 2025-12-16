<?php

namespace App\Entity;

use App\Repository\VotoNoticiaRepository;
use Doctrine\ORM\Mapping as ORM;

// Entidad que representa un voto con puntuación numérica sobre una noticia.
#[ORM\Entity(repositoryClass: VotoNoticiaRepository::class)]
class VotoNoticia
{
    // Identificador único del voto.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Usuario que realiza el voto.
    #[ORM\ManyToOne(inversedBy: 'votoNoticias')]
    private ?Usuario $usuario = null;
    
    // Noticia a la que pertenece el voto.
    #[ORM\ManyToOne(targetEntity: Noticia::class, inversedBy: 'votos')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Noticia $noticia = null;

    // Puntuación otorgada a la noticia (por ejemplo de 1 a 5).
    #[ORM\Column(type: 'integer')]
    private ?int $puntuacion = null;

    // Fecha/hora en la que se registró el voto.
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $fecha = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getNoticia(): ?Noticia
    {
        return $this->noticia;
    }

    public function setNoticia(?Noticia $noticia): static
    {
        $this->noticia = $noticia;

        return $this;
    }

    public function getPuntuacion(): ?int
    {
        return $this->puntuacion;
    }

    public function setPuntuacion(int $puntuacion): static
    {
        $this->puntuacion = $puntuacion;
        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;
        return $this;
    }
}