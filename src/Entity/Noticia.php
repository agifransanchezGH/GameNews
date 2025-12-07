<?php

namespace App\Entity;

use App\Repository\NoticiaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Nullable;

#[ORM\Entity(repositoryClass: NoticiaRepository::class)]
class Noticia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titulo = null;

    #[ORM\Column(length: 150)]
    private ?string $subtitulo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $cuerpo = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $fechaPublicacion = null;

    #[ORM\Column(length: 100)]
    private ?string $estado = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $valoracionPromedio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagen = null;
    
    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'Noticia', orphanRemoval: true)]
    
    private Collection $comentarios;

    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'noticia')]
    private Collection $comentario;

    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
        $this->comentario = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getSubtitulo(): ?string
    {
        return $this->subtitulo;
    }

    public function setSubtitulo(string $subtitulo): static
    {
        $this->subtitulo = $subtitulo;

        return $this;
    }

    public function getCuerpo(): ?string
    {
        return $this->cuerpo;
    }

    public function setCuerpo(string $cuerpo): static
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $fechaPublicacion): static
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getValoracionPromedio(): ?float
    {
        return $this->valoracionPromedio;
    }
    
    public function setValoracionPromedio(?float $valoracionPromedio): static
    {
        $this->valoracionPromedio = $valoracionPromedio;

        return $this;
    }

    // --- Nuevo getter/setter para imagen ---
    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): static
    {
        $this->imagen = $imagen;
        return $this;
    }
    /**
     * @return Collection<int, Comentario>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): static
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
            $comentario->setNoticia($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): static
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getNoticia() === $this) {
                $comentario->setNoticia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comentario>
     */
    public function getComentario(): Collection
    {
        return $this->comentario;
    }
}