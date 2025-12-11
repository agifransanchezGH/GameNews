<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Noticia;
use App\Entity\Categoria;
class EditorController extends AbstractController 
{
#[Route('/editor/dashboard', name: 'editor_dashboard')]
public function dashboard(EntityManagerInterface $em): Response
{
    $noticias = $em->getRepository(Noticia::class)->findAll();
    $categorias = $em->getRepository(Categoria::class)->findAll();

    return $this->render('editor/dashboard.html.twig', [
        'noticias' => $noticias,
        'categorias' => $categorias,
    ]);
}
}