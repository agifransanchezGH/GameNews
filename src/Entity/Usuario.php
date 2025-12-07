<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $correo = null;

    #[ORM\Column(length: 100)]
    private ?string $nombreUsuario = null;

    #[ORM\Column(length: 150)]
    private ?string $contraseña = null;

    #[ORM\Column(length: 100)]
    private ?string $rol = null;

    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'usuario')]
    private Collection $comentario;

    public function __construct()
    {
        $this->comentario = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): static
    {
        $this->correo = $correo;

        return $this;
    }

    public function getNombreUsuario(): ?string
    {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario(string $nombreUsuario): static
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    public function getContraseña(): ?string
    {
        return $this->contraseña;
    }

    public function setContraseña(string $contraseña): static
    {
        $this->contraseña = $contraseña;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    //Esto se creo automaticamente por symfony, son los metodos de UserInterface

    // UserInterface methods
    public function getRoles(): array
    {
        $roles = [];

        if ($this->rol) {
            // If roles are stored as a comma separated string, split them; otherwise add single role
            if (str_contains($this->rol, ',')) {
                $parts = array_map('trim', explode(',', $this->rol));
                $roles = array_merge($roles, $parts);
            } else {
                $roles[] = $this->rol;
            }
        }

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        // use the existing contraseña field as the password
        return $this->contraseña;
    }

    
    public function getSalt(): ?string
    {
        // bcrypt/argon2i do not require a separate salt
        return null;
    }

    public function getUsername(): string
    {
        // legacy method required by some parts of Symfony; delegate to nombreUsuario
        return (string) $this->nombreUsuario;
    }

    public function getUserIdentifier(): string
    {
        // modern method used by Symfony >=5.3
        return (string) $this->correo;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Comentario>
     */
    public function getComentario(): Collection
    {
        return $this->comentario;
    }

    public function addComentario(Comentario $comentario): static
    {
        if (!$this->comentario->contains($comentario)) {
            $this->comentario->add($comentario);
            $comentario->setUsuario($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): static
    {
        if ($this->comentario->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getUsuario() === $this) {
                $comentario->setUsuario(null);
            }
        }

        return $this;
    }
}
