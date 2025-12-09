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
use App\Entity\VotoNoticia;

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
public function votar(int $id, Request $request, NoticiaRepository $noticiaRepo, VotoNoticiaRepository $votoRepo, EntityManagerInterface $em): Response 
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

}