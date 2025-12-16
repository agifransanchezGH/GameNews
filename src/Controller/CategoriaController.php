<?php

namespace App\Controller;

use App\Entity\Categoria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Controlador para la gestión de categorías desde el panel de editor.
class CategoriaController extends AbstractController
{
    // Lista todas las categorías disponibles.
    #[Route('/editor/categorias', name: 'categoria_listar')]
    public function listar(EntityManagerInterface $em)
    {   
        //Validacion del rol del usuario
        $this->denyAccessUnlessGranted('ROLE_EDITOR');
        //Busqueda de todas las categorias en el repositorio de la calse categoria
        $categorias = $em->getRepository(Categoria::class)->findAll();

        return $this->render('editor/categorias.html.twig', [
            'categorias' => $categorias,
        ]);
    }

    // Crea una nueva categoría, validando que no exista otra con el mismo nombre.
    #[Route('/editor/categorias/nueva', name: 'categoria_nueva', methods: ['GET', 'POST'])]
public function crearCategoria(Request $request, EntityManagerInterface $em): Response
{   
    //Validacion del rol del usuario
    $this->denyAccessUnlessGranted('ROLE_EDITOR');

    if ($request->isMethod('POST')) {
        $nombre = $request->request->get('nombre');
        $descripcion = $request->request->get('descripcion');
        $categoriaExistente = $em->getRepository(Categoria::class)->findOneBy(['nombre' => $nombre]);  
        if ($categoriaExistente) {
            $this->addFlash('error', 'La categoría ya existe');
            return $this->redirectToRoute('categoria_nueva');
        }else{
            if ($nombre && $descripcion) {
            $categoria = new Categoria();
            $categoria->setNombre($nombre);
            $categoria->setDescripcion($descripcion);

            $em->persist($categoria);
            $em->flush();

            $this->addFlash('success', 'Categoría creada correctamente');
            return $this->redirectToRoute('categoria_listar');
        }
    }
        $this->addFlash('error', 'Todos los campos son obligatorios');
    }

    return $this->render('editor/crearCategoria.html.twig');
}


    // Elimina una categoría seleccionada por el editor.
    #[Route('/editor/categorias/eliminar/{id}', name: 'categoria_eliminar')]
    public function eliminar(EntityManagerInterface $em, int $id)
    {   
        $this->denyAccessUnlessGranted('ROLE_EDITOR');
        $categoria = $em->getRepository(Categoria::class)->find($id);
        if ($categoria) {
            $em->remove($categoria);
            $em->flush();
        }
        $this->addFlash('success', 'Categoria eliminada correctamente');
        return $this->redirectToRoute('categoria_listar');
    }
}