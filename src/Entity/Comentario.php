<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComentarioRepository::class)]
class Comentario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Contenido = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $fechaHora = null;

    #[ORM\Column(length: 50)]
    private ?string $estadoModeracion = null;
    
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $votosPositivos = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $votosNegativos = null;

    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Noticia $Noticia = null;

    #[ORM\ManyToOne(inversedBy: 'comentario')]
    private ?Usuario $usuario = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenido(): ?string
    {
        return $this->Contenido;
    }

    public function setContenido(?string $Contenido): static
    {
        $this->Contenido = $Contenido;

        return $this;
    }

    public function getFechaHora(): ?string
    {
        return $this->fechaHora;
    }

    public function setFechaHora(\DateTimeInterface $fechaHora): static
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    public function getEstadoModeracion(): ?string
    {
        return $this->estadoModeracion;
    }

    public function setEstadoModeracion(string $estadoModeracion): static
    {
        $this->estadoModeracion = $estadoModeracion;

        return $this;
    }

    public function getVotosPositivos(): ?int
    {
        return $this->votosPositivos;
    }

    public function setVotosPositivos(int $votosPositivos): static
    {
        $this->votosPositivos = $votosPositivos;

        return $this;
    }

    public function getVotosNegativos(): ?int
    {
        return $this->votosNegativos;
    }

    public function setVotosNegativos(int $votosNegativos): static
    {
        $this->votosNegativos = $votosNegativos;

        return $this;
    }

    public function getNoticia(): ?Noticia
    {
        return $this->Noticia;
    }

    public function setNoticia(?Noticia $Noticia): static
    {
        $this->Noticia = $Noticia;

        return $this;
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
}
