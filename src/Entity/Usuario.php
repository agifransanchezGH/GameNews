<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Entidad que representa a un usuario registrado en la aplicación.
// Implementa las interfaces de seguridad de Symfony para integrarse con el sistema de login.
#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Identificador único del usuario.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Correo electrónico del usuario, usado también como identificador de login.
    #[ORM\Column(length: 150)]
    private ?string $correo = null;

    // Nombre de usuario visible en la plataforma.
    #[ORM\Column(length: 100)]
    private ?string $nombreUsuario = null;

    // Contraseña encriptada del usuario.
    #[ORM\Column(length: 150)]
    private ?string $contraseña = null;

    // Rol principal del usuario (ROLE_USER, ROLE_ADMIN, ROLE_EDITOR).
    #[ORM\Column(length: 100)]
    private ?string $rol = null;

    // Estado de la cuenta: true = activa, false = suspendida.
    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private ?bool $estado = null;

    // Comentarios realizados por el usuario.
    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'usuario')]
    private Collection $comentario;

    // Votos realizados por el usuario sobre comentarios.
    #[ORM\OneToMany(targetEntity: VotoComentario::class, mappedBy: 'Usuario')]
    private Collection $votoComentarios;

    // Votos realizados por el usuario sobre noticias.
    #[ORM\OneToMany(targetEntity: VotoNoticia::class, mappedBy: 'usuario')]
    private Collection $votoNoticias;

    // Denuncias realizadas por el usuario sobre comentarios.
    #[ORM\OneToMany(targetEntity: DenunciaComentario::class, mappedBy: 'denunciante', orphanRemoval: true)]
    private Collection $denunciaComentario;

    // Inicializa las colecciones de relaciones.
    public function __construct()
    {
        $this->comentario = new ArrayCollection();
        $this->votoComentarios = new ArrayCollection();
        $this->votoNoticias = new ArrayCollection();
        $this->denunciaComentario = new ArrayCollection();
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

    public function getEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(bool $estado): static
    {
        $this->estado = $estado;

        return $this;
    }
    
    //Esto se creo automaticamente por symfony, son los metodos de UserInterface
    // Métodos requeridos por las interfaces de seguridad de Symfony.
    // Devuelve la lista de roles del usuario para el sistema de seguridad.
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

    
    public function getVotoComentarios(): Collection
    {
        return $this->votoComentarios;
    }

    public function addVotoComentario(VotoComentario $votoComentario): static
    {
        if (!$this->votoComentarios->contains($votoComentario)) {
            $this->votoComentarios->add($votoComentario);
            $votoComentario->setUsuario($this);
        }

        return $this;
    }

    public function removeVotoComentario(VotoComentario $votoComentario): static
    {
        if ($this->votoComentarios->removeElement($votoComentario)) {
            // set the owning side to null (unless already changed)
            if ($votoComentario->getUsuario() === $this) {
                $votoComentario->setUsuario(null);
            }
        }

        return $this;
    }

    
    public function getVotoNoticias(): Collection
    {
        return $this->votoNoticias;
    }

    public function addVotoNoticia(VotoNoticia $votoNoticia): static
    {
        if (!$this->votoNoticias->contains($votoNoticia)) {
            $this->votoNoticias->add($votoNoticia);
            $votoNoticia->setUsuario($this);
        }

        return $this;
    }

    public function removeVotoNoticia(VotoNoticia $votoNoticia): static
    {
        if ($this->votoNoticias->removeElement($votoNoticia)) {
            // set the owning side to null (unless already changed)
            if ($votoNoticia->getUsuario() === $this) {
                $votoNoticia->setUsuario(null);
            }
        }

        return $this;
    }

    
    public function getDenunciaComentario(): Collection
    {
        return $this->denunciaComentario;
    }

    public function addDenunciaComentario(DenunciaComentario $denunciaComentario): static
    {
        if (!$this->denunciaComentario->contains($denunciaComentario)) {
            $this->denunciaComentario->add($denunciaComentario);
            $denunciaComentario->setDenunciante($this);
        }

        return $this;
    }

    public function removeDenunciaComentario(DenunciaComentario $denunciaComentario): static
    {
        if ($this->denunciaComentario->removeElement($denunciaComentario)) {
            // set the owning side to null (unless already changed)
            if ($denunciaComentario->getDenunciante() === $this) {
                $denunciaComentario->setDenunciante(null);
            }
        }

        return $this;
    }
}
