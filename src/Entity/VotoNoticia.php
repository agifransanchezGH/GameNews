<?php

namespace App\Entity;

use App\Repository\VotoNoticiaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VotoNoticiaRepository::class)]
class VotoNoticia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'votoNoticias')]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'votoNoticias')]
    private ?Noticia $noticia = null;

    #[ORM\Column(type: 'integer')]
    private ?int $puntuacion = null;

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