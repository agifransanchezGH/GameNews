<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    
    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Noticia $Noticia = null;

    #[ORM\ManyToOne(inversedBy: 'comentario')]
    private ?Usuario $usuario = null;

    #[ORM\OneToMany(targetEntity: VotoComentario::class, mappedBy: 'comentario')]
    private Collection $votoComentarios;

    public function __construct()
    {
        $this->votoComentarios = new ArrayCollection();
    }


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

    /**
     * @return Collection<int, VotoComentario>
     */
    public function getVotoComentarios(): Collection
    {
        return $this->votoComentarios;
    }

    public function addVotoComentario(VotoComentario $votoComentario): static
    {
        if (!$this->votoComentarios->contains($votoComentario)) {
            $this->votoComentarios->add($votoComentario);
            $votoComentario->setComentario($this);
        }

        return $this;
    }

    public function removeVotoComentario(VotoComentario $votoComentario): static
    {
        if ($this->votoComentarios->removeElement($votoComentario)) {
            // set the owning side to null (unless already changed)
            if ($votoComentario->getComentario() === $this) {
                $votoComentario->setComentario(null);
            }
        }

        return $this;
    }

    public function getValor(): int
    {
        $positivos = $this->votoComentarios->filter(fn($v) => $v->getValor() === true)->count();
        $negativos = $this->votoComentarios->filter(fn($v) => $v->getValor() === false)->count();
        return $positivos - $negativos;
    }
}