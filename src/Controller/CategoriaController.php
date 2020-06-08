<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categoria")
 */
class CategoriaController extends AbstractController {

    /**
     * @Route("/listado", name="app_listado_categoria")
     */
    public function index(CategoriaRepository $categoriaRepository) {
        $categorias = $categoriaRepository->findAll();
        return $this->render('categoria/index.html.twig', [
                    'categorias' => $categorias,
        ]);
    }

    /**
     * @Route("/nueva", name="app_nueva_categoria")
     */
    public function nueva(CategoriaRepository $categoriaRepository, EntityManagerInterface $entityManager, Request $request) {
        $categoria = new Categoria();
        if ($this->isCsrfTokenValid('categoria', $request->request->get('_token'))) {
            $nombre = $request->request->get('nombre', null);
            $color = $request->request->get('color', null);
            $categoria->setNombre($nombre);
            $categoria->setColor($color);
            if ($nombre && $color) {
                $entityManager->persist($categoria);
                $entityManager->flush();
                $this->addFlash('success', 'Categoría creada correctamente');
                return $this->redirectToRoute('app_listado_categoria');
            } else {
                $this->addFlash('danger', 'El campo nombre es obligatorio');
                if ($color) {
                    $this->addFlash('danger', 'El campo color es obligatorio');
                }
            }
        }
        return $this->render('categoria/nueva.html.twig', [
                    'categoria' => $categoria
        ]);
    }

    /**
     * @Route("/{id}/editar", name="app_editar_categoria")
     */
    public function editar(Categoria $categoria, EntityManagerInterface $entityManager, Request $request) {
        if ($this->isCsrfTokenValid('categoria', $request->request->get('_token'))) {
            $nombre = $request->request->get('nombre', null);
            $color = $request->request->get('color', null);
            $categoria->setNombre($nombre);
            $categoria->setColor($color);
            if ($nombre && $color) {
                $entityManager->persist($categoria);
                $entityManager->flush();
                $this->addFlash('success', 'Categoría editada correctamente');
                return $this->redirectToRoute('app_listado_categoria');
            } else {
                $this->addFlash('danger', 'El campo nombre es obligatorio');
                if ($color) {
                    $this->addFlash('danger', 'El campo color es obligatorio');
                }
            }
        }
        return $this->render('categoria/editar.html.twig', [
                    'categoria' => $categoria
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_eliminar_categoria")
     */
    public function eliminar(Categoria $categoria, Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categoria);
        $entityManager->flush();
        $this->addFlash('success', 'Categoría eliminada correctamente');

        return $this->redirectToRoute('app_listado_categoria');
    }

}
