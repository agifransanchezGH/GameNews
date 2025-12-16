<?php

namespace App\Entity;

use App\Repository\VotoComentarioRepository;
use Doctrine\ORM\Mapping as ORM;

// Entidad que representa un voto (positivo/negativo) sobre un comentario.
#[ORM\Entity(repositoryClass: VotoComentarioRepository::class)]
class VotoComentario
{
    // Identificador único del voto.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    // Usuario que emite el voto.
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    // Comentario al que se aplica el voto.
    #[ORM\ManyToOne(targetEntity: Comentario::class, inversedBy: 'votos')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Comentario $comentario = null;

    // Valor del voto: true = positivo, false = negativo.
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $valor = null;


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

    public function getComentario(): ?Comentario
    {
        return $this->comentario;
    }

    public function setComentario(?Comentario $comentario): static
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getValor(): ?bool
    {
        return $this->valor;
    }

    public function setValor(?bool $valor): static
    {
        $this->valor = $valor;

        return $this;
    }
}