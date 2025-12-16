<?php

namespace App\Controller;

use App\Manager\NoticiaManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NoticiaRepository;
use App\Repository\VotoNoticiaRepository;
use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use App\Entity\VotoNoticia;
use App\Entity\Noticia;
use DateTimeImmutable;

class NoticiaController extends AbstractController
{
    #[Route('/noticia/{id}', name: 'app_pagina_noticia')]
    public function verNoticia(int $id, NoticiaManager $noticiaManager): Response
    {
        $noticia = $noticiaManager->getNoticia($id);

        if ($noticia) {
            return $this->render('pagina_noticia/noticia.html.twig', [
                'controller_name' => 'NoticiaController',
                'noticia' => $noticia,
            ]);
        } else {
            $this->addFlash('error', 'No se encontro la noticia');
            return $this->redirectToRoute("app_pagina_principal");
        }
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

        $puntuacion = (int) $request->request->get('puntuacion');

        $voto = $votoRepo->findOneBy([
            'usuario' => $usuario,
            'noticia' => $noticia
        ]);

        if ($voto) {
            $this->addFlash('error', 'ya voto esta noticia');
            return $this->redirectToRoute('app_pagina_noticia', ['id'=>$id]);
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
    #[Route('/editor/noticia/nueva', name: 'noticia_nueva', methods: ['GET', 'POST'])]
    public function nueva(CategoriaRepository $categoriaRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_EDITOR');
        $categorias = $categoriaRepository->findAll();

        return $this->render('editor/crearNoticias.html.twig', [
            'categorias' => $categorias,
        ]);
    }

    //Metodo que maneja la logica de creacion de una noticia
    #[Route('/editor/noticia/crear', name: 'noticia_crear', methods: ['GET', 'POST'])]
    public function crear(Request $request, EntityManagerInterface $em, CategoriaRepository $categoriaRepository): Response
    {
        if ($request->isMethod('POST')) {
            $titulo = $request->request->get('titulo');
            $subtitulo = $request->request->get('subtitulo');
            $cuerpo = $request->request->get('cuerpo');
            $categoriaId = $request->request->get('categoria');
            $categoria = $categoriaRepository->find($categoriaId);
            $estado = $request->request->get('estado');
            $fechaCreacion = new DateTimeImmutable();
            
            $imagenFile = $request->files->get('imagen');
            $nombreImagen = null;

            if (!$titulo || !$subtitulo || !$cuerpo){
                $this->addFlash('error', 'Titulo, subtitulo y cuerpo son campos obligatorios');
                    return $this->redirectToRoute('noticia_crear');
            }
            if ($imagenFile) {
                $nombreImagen = uniqid().'.'.$imagenFile->guessExtension();
                try {
                    $imagenFile->move(
                        $this->getParameter('imagenes_directorio'), // definido en services.yaml
                        $nombreImagen
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error al subir la imagen');
                    return $this->render("security/editarNoticia.html.twig");
                }
            }

            $noticia = new Noticia();
            $noticia->setTitulo($titulo);
            $noticia->setSubtitulo($subtitulo);
            $noticia->setCuerpo($cuerpo);
            $noticia->setEstado($estado);
            $noticia->setFechaPublicacion($fechaCreacion);
            $noticia->setCategoria($categoria);
            
            if ($nombreImagen) {
                $noticia->setImagen($nombreImagen);
            }
            $em->persist($noticia);
            $em->flush();

            $this->addFlash('success', 'Noticia creada correctamente');
            return $this->redirectToRoute('editor_dashboard');
        }

        return $this->render('editor/crearNoticias.html.twig', [
            'categorias' => $em->getRepository(Categoria::class)->findAll()
        ]);
    }

    #[Route('/noticia/editar/{id}', name: 'noticia_editar', methods: ['GET','POST'])]
    public function editar(Request $request, EntityManagerInterface $em, int $id, CategoriaRepository $categoriaRepository): Response
    {
        $noticia = $em->getRepository(Noticia::class)->find($id);
        if (!$noticia) {
            throw $this->createNotFoundException('Noticia no encontrada');
        }

        if ($request->isMethod('POST')) {
            $noticia->setTitulo($request->request->get('titulo'));
            $noticia->setSubtitulo($request->request->get('subtitulo'));
            $noticia->setCuerpo($request->request->get('cuerpo'));
            $noticia->setEstado($request->request->get('estado'));
            
            $categoriaId = $request->request->get('categoria');
            $noticia->setCategoria($categoriaRepository->find($categoriaId));
            
            $imagenFile = $request->files->get('imagen');
            if ($imagenFile) {
                $nombreImagen = uniqid().'.'.$imagenFile->guessExtension();
                try {
                    $imagenFile->move(
                        $this->getParameter('imagenes_directorio'),
                        $nombreImagen
                    );
                    $noticia->setImagen($nombreImagen);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error al subir la imagen');
                    return $this->render("security/editarNoticia.html.twig");
                }
            }

            $em->flush();
            $this->addFlash('success', 'Noticia actualizada correctamente');
            return $this->redirectToRoute('editor_dashboard');
        }

        return $this->render('editor/editarNoticia.html.twig', [
            'noticia' => $noticia,
            'categorias' => $em->getRepository(Categoria::class)->findAll()
        ]);
    }

    #[Route('/editor/noticia/{id}/eliminar', name: 'noticia_eliminar', methods: ['POST'])]
    public function eliminar(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_EDITOR');
        $noticia = $em->getRepository(Noticia::class)->find($id);

        if (!$noticia) {
            throw $this->createNotFoundException('Noticia no encontrada');
        }

        $em->remove($noticia);
        $em->flush();

        $this->addFlash('success', 'La noticia fue eliminada correctamente');
        return $this->redirectToRoute('editor_dashboard');
    }
}
