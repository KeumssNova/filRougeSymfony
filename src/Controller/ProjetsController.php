<?php

namespace App\Controller;

use App\Entity\Projets;
use App\Form\ProjetsType;
use App\Repository\ProjetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projets')]
class ProjetsController extends AbstractController
{
    #[Route('/projets', name: 'app_projets_index', methods: ['GET'])]
    public function index(ProjetsRepository $projetsRepository): Response
    {
        return $this->render('projets/indexProjets.html.twig', [
            'projets' => $projetsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_projets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projets();
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('app_projets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projets/newProjets.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projets_show', methods: ['GET'])]
    public function show(Projets $projet): Response
    {
        return $this->render('projets/showProjets.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_projets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projets $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_projets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projets/editProjets.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projets_delete', methods: ['POST'])]
    public function delete(Request $request, Projets $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $projet->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projets_index', [], Response::HTTP_SEE_OTHER);
    }
}
