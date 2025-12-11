<?php

namespace App\Controller;

use App\Manager\NoticiaManager;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\NoticiaRepository;

class PaginaPrincipalController extends AbstractController
{
    #[Route('/', name: 'app_pagina_principal')]
    public function index(NoticiaManager $noticiaManager, CategoriaRepository $categoriaRepository): Response
    {
        $noticias = $noticiaManager->getNoticias();
        $categorias = $categoriaRepository->findAll();

        return $this->render('pagina_principal/paginaPrincipal.html.twig', [
            'controller_name' => 'PaginaPrincipalController',
            'noticias' => $noticias,
            'categorias' => $categorias,
        ]);
    }

    #[Route('/buscar', name: 'buscar_contenido')]
    public function buscar(Request $request, NoticiaRepository $noticiaRepository, CategoriaRepository $categoriaRepository): Response 
    {
        $q = $request->query->get('q');
        $categoriaId = $request->query->get('categoria');
        $desde = $request->query->get('desde');
        $hasta = $request->query->get('hasta');

        // Construir filtros dinámicos
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