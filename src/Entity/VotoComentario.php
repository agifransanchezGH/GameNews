<?php

namespace App\Entity;

use App\Repository\VotoComentarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VotoComentarioRepository::class)]
class VotoComentario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: Comentario::class, inversedBy: 'votos')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Comentario $comentario = null;

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