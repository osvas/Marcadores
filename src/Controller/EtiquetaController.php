<?php

namespace App\Controller;

use App\Entity\Etiqueta;
use App\Form\EtiquetaType;
use App\Repository\EtiquetaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etiqueta")
 */
class EtiquetaController extends AbstractController {

    /**
     * @Route("/", name="etiqueta_index", methods={"GET"})
     */
    public function index(EtiquetaRepository $etiquetaRepository): Response {
        return $this->render('etiqueta/index.html.twig', [
                    'etiquetas' => $etiquetaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="etiqueta_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $etiquetum = new Etiqueta();
        $form = $this->createForm(EtiquetaType::class, $etiquetum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($etiquetum);
            $entityManager->flush();

            return $this->redirectToRoute('etiqueta_index');
        }

        return $this->render('etiqueta/new.html.twig', [
                    'etiquetum' => $etiquetum,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etiqueta_show", methods={"GET"})
     */
    public function show(Etiqueta $etiquetum): Response {
        return $this->render('etiqueta/show.html.twig', [
                    'etiquetum' => $etiquetum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etiqueta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etiqueta $etiquetum): Response {
        $form = $this->createForm(EtiquetaType::class, $etiquetum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('etiqueta_index');
        }

        return $this->render('etiqueta/edit.html.twig', [
                    'etiquetum' => $etiquetum,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etiqueta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Etiqueta $etiquetum): Response {
        if ($this->isCsrfTokenValid('delete' . $etiquetum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($etiquetum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('etiqueta_index');
    }

    /**
     * @Route("/nueva-etiqueta-ajax", name="nueva_etiqueta_ajax")
     */
    public function nuevaEtiquetaAjax(Request $request): Response {
        $creado = false;
        if ($request->isXmlHttpRequest()) {
            $etiquetum = new Etiqueta();
            $form = $this->createForm(EtiquetaType::class, $etiquetum, [
                'action' => $this->generateUrl('nueva_etiqueta_ajax')
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($etiquetum);
                $entityManager->flush();
                $this->addFlash('success', 'Etiqueta creada correctamente!');
                $creado = true;
                $etiquetum = new Etiqueta();
                $form = $this->createForm(EtiquetaType::class, $etiquetum, [
                    'action' => $this->generateUrl('nueva_etiqueta_ajax')
                ]);
            }
            
            return $this->json([
                'creado' => $creado,
                'form' => $this->render('etiqueta/_form.html.twig', [
                    'ajax' => true,
                    'form' => $form->createView()
                ])
            ]);
        }

        throw $this->createNotFoundException("no se que pasa");
    }

}
