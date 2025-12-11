<?php

namespace App\Controller;

use App\Manager\NoticiaManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NoticiaRepository;
use App\Repository\VotoNoticiaRepository;
use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use App\Entity\VotoNoticia;
use App\Entity\Noticia;

class NoticiaController extends AbstractController
{
    #[Route('/noticia/{id}', name: 'app_pagina_noticia')]
    public function index(int $id, NoticiaManager $noticiaManager): Response
    {
        $noticia = $noticiaManager->getNoticia($id);

        return $this->render('pagina_noticia/noticia.html.twig', [
            'controller_name' => 'NoticiaController',
            'noticia' => $noticia,
        ]);
    }

    #[Route('/noticia/{id}/votar', name: 'noticia_votar')]
    public function votarNoticia(int $id, Request $request, NoticiaRepository $noticiaRepo, VotoNoticiaRepository $votoRepo, EntityManagerInterface $em): Response
    {
        $usuario = $this->getUser();
        if (!$usuario) {
            $this->addFlash('error', 'Debes iniciar sesión para votar');
            return $this->redirectToRoute('app_iniciar_sesion');
        }

        $noticia = $noticiaRepo->find($id);
        if (!$noticia) {
            $this->addFlash('error', 'Noticia no encontrada');
            return $this->redirectToRoute('app_pagina_principal');
        }

        $puntuacion = (int) $request->request->get('puntuacion'); // valor 1–5

        if ($puntuacion < 1 || $puntuacion > 5) {
            $this->addFlash('error', 'La puntuación debe estar entre 1 y 5');
            return $this->redirectToRoute('noticia_ver', ['id' => $id]);
        }

        $voto = $votoRepo->findOneBy([
            'usuario' => $usuario,
            'noticia' => $noticia
        ]);

        if ($voto) {
            $voto->setPuntuacion($puntuacion);
            $voto->setFecha(new \DateTime());
        } else {
            $voto = new VotoNoticia();
            $voto->setUsuario($usuario);
            $voto->setNoticia($noticia);
            $voto->setPuntuacion($puntuacion);
            $voto->setFecha(new \DateTime());
            $em->persist($voto);
        }

        $em->flush();

        $this->addFlash('success', 'Tu voto ha sido registrado');
        return $this->redirectToRoute('app_pagina_noticia', ['id' => $id]);
    }

    #[Route('/buscar', name: 'buscar_contenido')]
    public function buscar(Request $request, EntityManagerInterface $em): Response
    {
        $termino = $request->query->get('q'); // término de búsqueda
        $categoriaId = $request->query->get('categoria'); // filtro por categoría
        $fechaDesde = $request->query->get('desde');
        $fechaHasta = $request->query->get('hasta');

        // Validaciones de RN1 y RN2

        //quitar esto
        if ($termino && (strlen($termino) > 20 || !ctype_alnum(str_replace(' ', '', $termino)))) {
            $this->addFlash('error', 'El término de búsqueda debe ser alfanumérico y de máximo 20 caracteres.');
            return $this->redirectToRoute('app_pagina_principal');
        }

        $qb = $em->getRepository(Noticia::class)->createQueryBuilder('n');

        if ($termino) {
            $qb->andWhere('n.titulo LIKE :termino OR n.subtitulo LIKE :termino OR n.cuerpo LIKE :termino')
                ->setParameter('termino', '%' . $termino . '%');
        }

        if ($categoriaId) {
            $qb->andWhere('n.categoria = :cat')
                ->setParameter('cat', $categoriaId);
        }

        if ($fechaDesde) {
            $qb->andWhere('n.fechaPublicacion >= :desde')
                ->setParameter('desde', new \DateTime($fechaDesde));
        }

        if ($fechaHasta) {
            $qb->andWhere('n.fechaPublicacion <= :hasta')
                ->setParameter('hasta', new \DateTime($fechaHasta));
        }

        $resultados = $qb->getQuery()->getResult();

        return $this->render('paginaPrincipal.html.twig', [
            'noticias' => $resultados,
            'busqueda' => $termino,
        ]);
    }


    //Metodo para renderizar la template con el formulario para crear una nueva noticia
    #[Route('/editor/noticia/nueva', name: 'noticia_nueva', methods: ['GET'])]
    public function nueva(CategoriaRepository $categoriaRepository): Response
    {
    $categorias = $categoriaRepository->findAll();

    return $this->render('editor/crearNoticias.html.twig', [
        'categorias' => $categorias,
    ]);
    }

    //Metodo que maneja la logica de creacion de una noticia
    #[Route('/editor/noticia/crear', name: 'noticia_crear', methods: ['GET', 'POST'])]
    public function crearNoticia(Request $request, EntityManagerInterface $em): Response
    {
        $titulo = $request->request->get('titulo');
        $subtitulo = $request->request->get('subtitulo');
        $cuerpo = $request->request->get('cuerpo');
        $imagen = $request->request->get('imagen');
        $estado = $request->request->get('estado');
        $categoriaId = $request->request->get('categoria');
        $categoria = null;

        if ($categoriaId) {
            $categoria = $em->getRepository(Categoria::class)->find((int)$categoriaId);
            if (!$categoria) {
                throw $this->createNotFoundException('Categoría no encontrada');
            }
        }

        $noticia = new Noticia();
        $noticia->setTitulo($titulo);
        $noticia->setSubtitulo($subtitulo);
        $noticia->setCuerpo($cuerpo);
        $noticia->setImagen($imagen);
        $noticia->setEstado($estado);
        $noticia->setFechaPublicacion(new \DateTimeImmutable());
        $noticia->setCategoria($categoria); // puede ser null si no se seleccionó

        $em->persist($noticia);
        $em->flush();

        return $this->redirectToRoute('app_pagina_principal');
    }

    #[Route('/editor/noticia/{id}/editar', name: 'noticia_editar', methods: ['POST'])]
    public function editar(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $noticia = $em->getRepository(Noticia::class)->find($id);

        if (!$noticia) {
            throw $this->createNotFoundException('Noticia no encontrada');
        }

        $titulo = $request->request->get('titulo');
        $cuerpo = $request->request->get('cuerpo');
        $imagen = $request->request->get('imagen');
        $categoriaId = $request->request->get('categoria');

        $categoria = $em->getRepository(Categoria::class)->find($categoriaId);

        $noticia->setTitulo($titulo);
        $noticia->setCuerpo($cuerpo);
        $noticia->setImagen($imagen);
        $noticia->setCategoria($categoria);

        $em->flush();

        return $this->redirectToRoute('app_pagina_principal');
    }
}
