<?php
namespace App\DataFixtures;

use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioFixtures extends Fixture
{
    private UserPasswordHasherInterface $hash;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hash = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usuarioAdmin = new Usuario();
        $usuarioAdmin->setNombreUsuario('admin');
        $usuarioAdmin->setCorreo('admin@gamenews.com');
        $usuarioAdmin->setRol('ROLE_ADMIN');
        $usuarioAdmin->setContraseña($this->hash->hashPassword($usuarioAdmin, '123'));
        $usuarioAdmin->setEstado(true);
        $manager->persist($usuarioAdmin);

        $usuarioEditor = new Usuario();
        $usuarioEditor->setNombreUsuario('editor');
        $usuarioEditor->setCorreo('editor@gamenews.com');
        $usuarioEditor->setRol('ROLE_EDITOR');
        $usuarioEditor->setContraseña($this->hash->hashPassword($usuarioEditor, '123'));
        $usuarioEditor->setEstado(true);
        $manager->persist($usuarioEditor);

        $usuario = new Usuario();
        $usuario->setNombreUsuario('usuario');
        $usuario->setCorreo('usuario@gamenews.com');
        $usuario->setRol('ROLE_USER');
        $usuario->setContraseña($this->hash->hashPassword($usuario, '123'));
        $usuario->setEstado(true);
        $manager->persist($usuario);

        $manager->flush();
    }
}
