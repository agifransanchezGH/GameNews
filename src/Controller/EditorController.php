<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Noticia;
use App\Entity\Categoria;

// Controlador del panel de editor: muestra un resumen de noticias y categorías.
class EditorController extends AbstractController 
{
// Muestra el dashboard del editor con las noticias y categorías disponibles.
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