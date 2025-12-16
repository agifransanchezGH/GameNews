<?php

namespace App\Entity;

use App\Repository\DenunciaComentarioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Entidad que representa una denuncia realizada por un usuario sobre un comentario.
#[ORM\Entity(repositoryClass: DenunciaComentarioRepository::class)]
class DenunciaComentario
{
    // Identificador único de la denuncia.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; 

    // Tipo de denuncia (spam, insultos, contenido inapropiado, etc.).
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $tipo = null;

    // Descripción opcional que el usuario indica sobre el motivo.
    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    // Fecha y hora en que se creó la denuncia.
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $fecha = null;

    // Usuario que ha realizado la denuncia.
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $denunciante = null;

    // Estado actual de la denuncia (pendiente, resuelta, rechazada, etc.).
    #[ORM\Column(length: 30)]
    private ?string $estado = null;

    // Comentario denunciado.
    #[ORM\ManyToOne(inversedBy: 'denunciaComentarios')]
    private ?Comentario $comentario = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): static
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

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

    public function getDenunciante(): ?Usuario
    {
        return $this->denunciante;
    }

    public function setDenunciante(?Usuario $denunciante): static
    {
        $this->denunciante = $denunciante;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getComentario(): ?Comentario
    {
        return $this->comentario;
    }

    public function setComentario(?Comentario $comentario): static
    {
        $this->comentario = $comentario;

        return $this;
    }

}
