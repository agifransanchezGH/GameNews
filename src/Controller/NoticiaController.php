<?php
namespace App\Controller;

use App\Manager\NoticiaManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}