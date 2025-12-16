<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\NoticiaRepository;
use App\Entity\Noticia;

// Controlador de la página principal: lista noticias, categorías y gestiona búsqueda básica.
class PaginaPrincipalController extends AbstractController
{
    // Muestra la página principal con el listado de noticias y categorías.
    #[Route('/', name: 'app_pagina_principal')]
    public function listado(EntityManagerInterface $em, CategoriaRepository $categoriaRepository): Response
    {
        $noticias = $em->getRepository(Noticia::class)->findBy([], ['fechaPublicacion' => 'DESC']);
        $categorias = $categoriaRepository->findAll();

        $usuario = $this->getUser();

        // Comprueba si hay una sesión iniciada; si no, se navega como lector.
        // Si hay usuario logueado, se verifica que la cuenta siga activa.
        if ($usuario === null) {
            return $this->render('pagina_principal/paginaPrincipal.html.twig', [
                'controller_name' => 'PaginaPrincipalController',
                'noticias' => $noticias,
                'categorias' => $categorias,
            ]);
        } else {
            // Verificación de estado de cuenta.
            // getEstado() devuelve true si la cuenta está activa, false si está suspendida. (Marca error pero funciona)
            if ($usuario && !$usuario->getEstado()) {
                $this->addFlash('error', 'Tu cuenta está suspendida. Puedes navegar como lector sin iniciar sesión.Contacte con un administrador');
                // Cierra la sesión para que el usuario vuelva a ser lector. (setSecurityTokenStorage es parte de AbstractController)
                $this->container->get('security.token_storage')->setToken(null);
            }
        }

        return $this->render('pagina_principal/paginaPrincipal.html.twig', [
            'controller_name' => 'PaginaPrincipalController',
            'noticias' => $noticias,
            'categorias' => $categorias,
        ]);
    }

    // Acción de búsqueda de noticias por texto, categoría y rango de fechas desde la página principal.
    #[Route('/buscar', name: 'buscar_contenido')]
    public function buscar(Request $request, NoticiaRepository $noticiaRepository, CategoriaRepository $categoriaRepository): Response
    {
        //Consulta personalizada a la base de datos, a traves de noticiaRepository
        $q = $request->query->get('q');
        $categoriaId = $request->query->get('categoria');
        $desde = $request->query->get('desde');
        $hasta = $request->query->get('hasta');

        $qb = $noticiaRepository->createQueryBuilder('n');
        if ($q) {
            $qb->andWhere('n.titulo LIKE :q OR n.cuerpo LIKE :q')
                ->setParameter('q', '%' . $q . '%');
        }
        if ($categoriaId) {
            $qb->andWhere('n.categoria = :categoria')
                ->setParameter('categoria', $categoriaId);
        }
        if ($desde) {
            $qb->andWhere('n.fechaPublicacion >= :desde')
                ->setParameter('desde', new \DateTimeImmutable($desde));
        }
        if ($hasta) {
            $qb->andWhere('n.fechaPublicacion <= :hasta')
                ->setParameter('hasta', new \DateTimeImmutable($hasta));
        }

        $noticias = $qb->getQuery()->getResult();
        $categorias = $categoriaRepository->findAll();

        return $this->render('pagina_principal/paginaPrincipal.html.twig', [
            'noticias' => $noticias,
            'categorias' => $categorias,
            'busqueda' => $q,
        ]);
    }
}
