<?php

namespace App\Entity;

use App\Repository\EstadisticaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Entidad que representa un valor de la estadistica.
#[ORM\Entity(repositoryClass: EstadisticaRepository::class)]
class Estadistica
{
    // Identificador único del registro estadístico.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Tipo de estadística (por ejemplo: visitas, comentarios, votos, etc.).
    #[ORM\Column(length: 150)]
    private ?string $tipo = null;

    // Fecha/hora desde la cual se mide la estadística.
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $fechaDesde = null;

    // Fecha/hora hasta la cual se mide la estadística.
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $fechaHasta = null;

    // Valor numérico de la estadística en el rango de fechas.
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $valor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getFechaDesde(): ?\DateTimeInterface
    {
        return $this->fechaDesde;
    }

    public function setFechaDesde(\DateTimeInterface $fechaDesde): static
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    public function getFechaHasta(): ?\DateTimeInterface
    {
        return $this->fechaHasta;
    }

    public function setFechaHasta(\DateTimeInterface $fechaHasta): static
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(int $valor): static
    {
        $this->valor = $valor;

        return $this;
    }
}

