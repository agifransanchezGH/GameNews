<?php

namespace App\Controller;

use App\Entity\Categoria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriaController extends AbstractController
{
    #[Route('/editor/categorias', name: 'categoria_listar')]
    public function listar(EntityManagerInterface $em)
    {   
        $this->denyAccessUnlessGranted('ROLE_EDITOR');
        $categorias = $em->getRepository(Categoria::class)->findAll();
        return $this->render('editor/categorias.html.twig', [
            'categorias' => $categorias,
        ]);
    }

    #[Route('/editor/categorias/nueva', name: 'categoria_nueva')]
public function nueva(Request $request, EntityManagerInterface $em): Response
{
    $this->denyAccessUnlessGranted('ROLE_EDITOR');
    if ($request->isMethod('POST')) {
        $nombre = $request->request->get('nombre');
        $descripcion = $request->request->get('descripcion');

        if ($nombre && $descripcion) {
            $categoria = new Categoria();
            $categoria->setNombre($nombre);
            $categoria->setDescripcion($descripcion);

            $em->persist($categoria);
            $em->flush();

            $this->addFlash('success', 'Categoría creada correctamente');
            return $this->redirectToRoute('categoria_listar');
        }

        $this->addFlash('error', 'Todos los campos son obligatorios');
    }

    return $this->render('editor/crearCategoria.html.twig');
}


    #[Route('/editor/categorias/eliminar/{id}', name: 'categoria_eliminar')]
    public function eliminar(EntityManagerInterface $em, int $id)
    {   
        $this->denyAccessUnlessGranted('ROLE_EDITOR');
        $categoria = $em->getRepository(Categoria::class)->find($id);
        if ($categoria) {
            $em->remove($categoria);
            $em->flush();
        }
        return $this->redirectToRoute('categoria_listar');
    }
}