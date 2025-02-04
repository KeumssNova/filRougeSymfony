<?php

namespace App\Controller;

use App\Classes\ManageSession;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ManagingSessionController extends AbstractController
{
    #[Route('/managing/session', name: 'app_managing_session')]
    public function index(ManageSession $team): Response
    {
        // $team->remove();
        // dd($team->getUser());
        return $this->render('managing_session/manageSession.html.twig', [
            'controller_name' => 'ManagingSessionController',
        ]);
    }

    #[Route('/managing/session/add/{id}', name: 'app_managing_session_add')]
    public function addUserToSession($id, ManageSession $session, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        if ($user) {
            $session->addUser($user);
        }

        return $this->redirectToRoute('app_projets_affecter');
    }

}
