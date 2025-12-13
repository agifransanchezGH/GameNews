<?php

namespace App\DataFixtures;

use App\Entity\Noticia;
use App\Entity\Categoria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NoticiaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Crear categorías de ejemplo
        $categoriaAccion = new Categoria();
        $categoriaAccion->setNombre('Acción');
        $categoriaAccion->setDescripcion('Noticias sobre juegos de acción');
        $manager->persist($categoriaAccion);

        $categoriaAventura = new Categoria();
        $categoriaAventura->setNombre('Aventura');
        $categoriaAventura->setDescripcion('Noticias sobre juegos de aventura');
        $manager->persist($categoriaAventura);

        // Crear noticia 1 y asignar categoría
        $noticia = new Noticia();
        $noticia->setTitulo('Explosiones y adrenalina en el nuevo shooter');
        $noticia->setSubtitulo('El juego que redefine la acción');
        $noticia->setCuerpo('La última entrega de la saga llega con combates intensos, mapas dinámicos y un arsenal renovado. Los jugadores podrán experimentar batallas épicas con gráficos de última generación.');
        $noticia->setFechaPublicacion(new \DateTimeImmutable('2023-06-15'));
        $noticia->setEstado('publicado');
        $noticia->setValoracionPromedio(4.5);
        $noticia->setImagen('shooter_accion.jpeg');
        $noticia->setCategoria($categoriaAccion); // asignar categoría

        // Crear noticia 2 y asignar categoría
        $noticia1 = new Noticia();
        $noticia1->setTitulo('Explora mundos mágicos en la nueva aventura');
        $noticia1->setSubtitulo('Un viaje inolvidable te espera');
        $noticia1->setCuerpo('Embárcate en una travesía llena de misterios, criaturas fantásticas y paisajes deslumbrantes. El juego invita a descubrir secretos ocultos y vivir una experiencia narrativa profunda.');
        $noticia1->setFechaPublicacion(new \DateTimeImmutable('2023-06-15'));
        $noticia1->setEstado('publicado');
        $noticia1->setValoracionPromedio(4.5);
        $noticia1->setImagen('juego_aventura.jpeg');
        $noticia1->setCategoria($categoriaAventura); // asignar categoría

        // Persistir noticias
        $manager->persist($noticia);
        $manager->persist($noticia1);

        // Guardar todo
        $manager->flush();
    }
}