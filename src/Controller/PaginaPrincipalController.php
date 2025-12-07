<?php
namespace App\Controller;

use App\Manager\NoticiaManager;
use App\Repository\NoticiaRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaginaPrincipalController extends AbstractController
{
    #[Route('/', name: 'app_pagina_principal')]
    public function index(NoticiaManager $noticiaManager): Response
    {
        $noticias = $noticiaManager->getNoticias();

        return $this->render('pagina_principal/paginaPrincipal.html.twig', [
            'controller_name' => 'PaginaPrincipalController',
            'noticias' => $noticias,
        ]);
    }
}