<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_accueil")
     */
    public function accueil(Request $request):Response
    {
        return $this->render('main/accueil.html.twig');
    }
    /**
     * @Route("/participant/liste_participant", name="liste_participants")
     */
    public function list(ParticipantRepository $participantRepository):Response
    {
        $participants = $participantRepository->findAll();

        return $this->render('participant/liste_participant.html.twig',[
            "participants" => $participants
        ]);
    }
    /**
     * @Route("/detail_participant/{id}", name="detail_participant")
     */
    public function detail(int $id, ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->find($id);
        return $this->render('participant/detail_participant.html.twig',[
            "participant" => $participant
        ]);
    }
}
