<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// Entidad que representa un comentario realizado por un usuario en una noticia.
#[ORM\Entity(repositoryClass: ComentarioRepository::class)]
class Comentario
{
    // Identificador único del comentario.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Texto del comentario escrito por el usuario.
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Contenido = null;

    // Momento en el que se creó el comentario.
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $fechaHora = null;

    // Estado de moderación del comentario (normal, pendiente, borrado, etc.).
    #[ORM\Column(length: 50)]
    private ?string $estadoModeracion = null;
    
    // Noticia a la que pertenece este comentario.
    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Noticia $Noticia = null;

    // Usuario que realizó el comentario.
    #[ORM\ManyToOne(inversedBy: 'comentario')]
    private ?Usuario $usuario = null;

    // Votos (positivos o negativos) asociados a este comentario.
    #[ORM\OneToMany(targetEntity: VotoComentario::class, mappedBy: 'comentario', cascade: ['remove'], orphanRemoval: true)]
    private Collection $votoComentarios;

    // Denuncias que otros usuarios han hecho sobre este comentario.
    #[ORM\OneToMany(targetEntity: DenunciaComentario::class, mappedBy: 'comentario', cascade: ['remove'], orphanRemoval: true)]
    private Collection $denunciaComentarios;

    public function __construct()
    {
        $this->votoComentarios = new ArrayCollection();
        $this->denunciaComentarios = new ArrayCollection();
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

    // Devuelve el valor neto del comentario: votos positivos - votos negativos.
    public function getValor(): int
    {
        $positivos = $this->votoComentarios->filter(fn($v) => $v->getValor() === true)->count();
        $negativos = $this->votoComentarios->filter(fn($v) => $v->getValor() === false)->count();
        return $positivos - $negativos;
    }

    
    public function getDenunciaComentarios(): Collection
    {
        return $this->denunciaComentarios;
    }

    public function addDenunciaComentario(DenunciaComentario $denunciaComentario): static
    {
        if (!$this->denunciaComentarios->contains($denunciaComentario)) {
            $this->denunciaComentarios->add($denunciaComentario);
            $denunciaComentario->setComentario($this);
        }

        return $this;
    }

    public function removeDenunciaComentario(DenunciaComentario $denunciaComentario): static
    {
        if ($this->denunciaComentarios->removeElement($denunciaComentario)) {
            // set the owning side to null (unless already changed)
            if ($denunciaComentario->getComentario() === $this) {
                $denunciaComentario->setComentario(null);
            }
        }

        return $this;
    }
}