<?php

namespace App\Controller;

use App\Classes\Search;
use App\Entity\Projets;
use App\Form\ProjetsType;
use App\Form\SearchType;
use App\Repository\UserRepository;
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
        $users = $projet->getUser();
        return $this->render('projets/showProjets.html.twig', [
            'projet' => $projet,
            'users' => $users
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
    // #[Route('/affecter/{id}', name: 'app_projets_affecter', methods: ['GET'])]
    // public function affecter(Request $request, UserRepository $userRepository): Response
    // {
    //     $users = $userRepository->findAll();
    //     $search = new Search();
    //     $form = $this->createForm(SearchType::class, $search);

    //     $form->handleRequest($request);
    //     if($form->isSubmitted() && $form->isValid()) {
    //         $users = $userRepository->findBySearch($search);
    //     }

    //     return $this->render('projets/affecterProjets.html.twig', [
    //         'users' => $users,
    //         'form' => $form->createView()
    //     ]);
    // }
    #[Route('/affecter/{id}', name: 'app_projets_affecter', methods: ['GET', 'POST'])]
    public function affecter(Projets $projets, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $userRepository->findBySearch($search);
        }

        // Vérifier si le formulaire d'affectation est soumis
        if ($request->isMethod('POST')) {
            $userIds = $request->request->all('selected_users');

            foreach ($userIds as $userId) {
                $user = $userRepository->find($userId);
                if ($user && !$projets->getUser()->contains($user)) { // Correction ici
                    $projets->addUser($user);
                }
            }

            $entityManager->persist($projets);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateurs affectés avec succès.');
            return $this->redirectToRoute('app_projets_affecter', ['id' => $projets->getId()]);
        }

        return $this->render('projets/affecterProjets.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
            'projets' => $projets,
        ]);
    }
    #[Route('/{projetId}/remove-user/{userId}', name: 'app_projets_remove_user', methods: ['POST'])]
    public function removeUser(
        int $projetId,
        int $userId,
        ProjetsRepository $projetsRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $projet = $projetsRepository->find($projetId);
        $user = $userRepository->find($userId);

        if (!$projet || !$user) {
            $this->addFlash('error', 'Projet ou utilisateur introuvable.');
            return $this->redirectToRoute('app_projets_show', ['id' => $projetId]);
        }

        if ($projet->getUser()->contains($user)) {
            $projet->removeUser($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur retiré du projet avec succès.');
        } else {
            $this->addFlash('error', 'Cet utilisateur n\'est pas affecté à ce projet.');
        }

        return $this->redirectToRoute('app_projets_show', ['id' => $projetId]);
    }



}
