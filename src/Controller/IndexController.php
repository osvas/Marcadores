<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MarcadorRepository;
use App\Repository\CategoriaRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EtiquetaRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class IndexController extends AbstractController {

    public const ELEMENTOS_POR_PAGINA = 10;

    /**
     * @Route("/buscar-etiquetas", name="app_buscar_etiquetas")
     */
    public function buscarEtiquetas(EtiquetaRepository $etiquetaRepository, Request $request): Response {
        if ($request->isXmlHttpRequest()) {
            $busqueda = $request->get('q');
            $etiquetas = $etiquetaRepository->buscarPorNombre($busqueda);

            return $this->json($etiquetas);
        }

        throw $this->createNotFoundException();
    }

    /**
     * @Route("/buscar/{busqueda}/{pagina}", name="app_busqueda", defaults={"busqueda": "", "pagina": 1}, requirements={"pagina"="\d+"})
     */
    public function busqueda(string $busqueda, int $pagina, MarcadorRepository $marcadorRepository, Request $request) {
        $formularioBusqueda = $this->createForm(\App\Form\BuscadorType::class);
        $formularioBusqueda->handleRequest($request);
        $marcadores = [];
        if ($formularioBusqueda->isSubmitted()) {
            if ($formularioBusqueda->isValid()) {
                $busqueda = $formularioBusqueda->get('busqueda')->getData();
            }
        }

        if (!empty($busqueda)) {
            $marcadores = $marcadorRepository->buscarPorNombre($busqueda, $pagina, self::ELEMENTOS_POR_PAGINA);
        }

        if (!empty($busqueda) || $formularioBusqueda->isSubmitted()) {
            return $this->render('index/index.html.twig', [
                        'formulario_busqueda' => $formularioBusqueda->createView(),
                        'marcadores' => $marcadores,
                        'pagina' => $pagina,
                        'parametros_ruta' => [
                            'busqueda' => $busqueda
                        ],
                        'elementos_por_pagina' => self::ELEMENTOS_POR_PAGINA
            ]);
        }

        return $this->render('comunes/_buscador.html.twig', [
                    'formulario_busqueda' => $formularioBusqueda->createView()
        ]);
    }

    /**
     * @Route("/editar-favorito", name="app_editar_favorito")
     */
    public function editarFavorito(MarcadorRepository $marcadorRepository, Request $request) {
        if ($request->isXmlHttpRequest()) {
            $actualizado = true;
            $idMarcador = $request->get('id');
            $entityManager = $this->getDoctrine()->getManager();
            $marcador = $marcadorRepository->findOneById($idMarcador);
            $marcador->setFavorito(!$marcador->getFavorito());

            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                $actualizado = false;
            }

            return $this->json(['actualizado' => $actualizado]);
        }

        throw $this->createNotFoundException();
    }

    /**
     * @Route("/favoritos/{pagina}", name="app_favoritos", defaults={"pagina": 1}, requirements={"pagina"="\d+"})
     */
    public function favoritos(int $pagina, MarcadorRepository $marcadorRepository) {
        $marcadores = $marcadorRepository->buscarPorFavoritos($pagina, self::ELEMENTOS_POR_PAGINA);

        return $this->render('index/index.html.twig', [
                    'marcadores' => $marcadores,
                    'pagina' => $pagina,
                    'elementos_por_pagina' => self::ELEMENTOS_POR_PAGINA
        ]);
    }

    /**
     * @Route("/{categoria}/{pagina}", name="app_index", defaults={"categoria": "todas", "pagina": 1}, requirements={"pagina"="\d+"}, methods={"GET"})
     */
    public function index(
            string $categoria,
            int $pagina,
            CategoriaRepository $categoriaRepository,
            MarcadorRepository $marcadorRepository,
            TranslatorInterface $translator
    ) {
        $elementosPorPagina = self::ELEMENTOS_POR_PAGINA;

        $categoria = (int) $categoria > 0 ? (int) $categoria : $categoria;
        if (is_int($categoria)) {
            $categoria = "todas";
            $pagina = $categoria;
        }

        if ($categoria && "todas" !== $categoria) {
            if (!$categoriaRepository->findByNombre($categoria)) {
                throw $this->createNotFoundException($translator->trans("La categoría \"{categoria}\" no existe.", [
                    '{categoria}' => $categoria
                ], 'messages'));
            }
            $marcadores = $marcadorRepository->buscarPorNombreCategoria($categoria, $pagina, $elementosPorPagina);
        } else {
            $marcadores = $marcadorRepository->buscarTodos($pagina, $elementosPorPagina);
        }

        return $this->render('index/index.html.twig', [
                    'marcadores' => $marcadores,
                    'pagina' => $pagina,
                    'parametros_ruta' => [
                        'categoria' => $categoria
                    ],
                    'elementos_por_pagina' => $elementosPorPagina
        ]);
    }

}
