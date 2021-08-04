<?php

namespace App\Controller;

use App\Entity\Urls;
use App\Form\UrlsType;
use App\Repository\UrlsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/urls')]
class UrlsController extends AbstractController
{
    #[Route('/', name: 'urls_index', methods: ['GET'])]
    public function index(UrlsRepository $urlsRepository): Response
    {
        return $this->render('urls/index.html.twig', [
            'urls' => $urlsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'urls_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $url = new Urls();
        $form = $this->createForm(UrlsType::class, $url);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($url);
            $entityManager->flush();

            return $this->redirectToRoute('urls_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('urls/new.html.twig', [
            'url' => $url,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'urls_show', methods: ['GET'])]
    public function show(Urls $url): Response
    {
        return $this->render('urls/show.html.twig', [
            'url' => $url,
        ]);
    }

    #[Route('/{id}/edit', name: 'urls_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Urls $url): Response
    {
        $form = $this->createForm(UrlsType::class, $url);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('urls_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('urls/edit.html.twig', [
            'url' => $url,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'urls_delete', methods: ['POST'])]
    public function delete(Request $request, Urls $url): Response
    {
        if ($this->isCsrfTokenValid('delete'.$url->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($url);
            $entityManager->flush();
        }

        return $this->redirectToRoute('urls_index', [], Response::HTTP_SEE_OTHER);
    }
}
