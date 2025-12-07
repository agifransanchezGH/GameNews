<?php

namespace App\DataFixtures;

use App\Entity\Noticia;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NoticiaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $noticia = new Noticia();
        $noticia->setTitulo('Ejemplo de noticia');
        $noticia->setSubtitulo('Subtítulo de ejemplo');
        $noticia->setCuerpo('Contenido completo de la noticia.');
        $noticia->setFechaPublicacion(new \DateTimeImmutable('2023-06-15'));
        $noticia->setEstado('publicada');
        $noticia->setValoracionPromedio(4.5);
        $noticia->setImagen('blox.jpeg');
        
        $noticia1 = new Noticia();
        $noticia1->setTitulo('Ejemplo de noticia1');
        $noticia1->setSubtitulo('Subtítulo de ejemplo1');
        $noticia1->setCuerpo('Contenido completo de la noticia.1');
        $noticia1->setFechaPublicacion(new \DateTimeImmutable('2023-06-15'));
        $noticia1->setEstado('publicada');
        $noticia1->setValoracionPromedio(4.5);
        $noticia1->setImagen('Roblox.jpeg');

        $manager->persist($noticia);
        $manager->persist($noticia1);
        $manager->flush();
    }
}