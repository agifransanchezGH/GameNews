<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

// Entidad que representa una categoría de noticias.
#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
class Categoria
{
    // Identificador único de la categoría.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Nombre visible de la categoría (por ejemplo: "Acción", "E-Sports"...).
    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    // Descripción breve de la categoría.
    #[ORM\Column(length: 150)]
    private ?string $descripcion = null;

    // Noticias asociadas a esta categoría.
    #[ORM\OneToMany(mappedBy: 'categoria', targetEntity: Noticia::class)]
    private Collection $noticias;

    public function __construct()
    {
    $this->noticias = new ArrayCollection();
    }

public function getNoticias(): Collection
    {
        return $this->noticias;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

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
}
