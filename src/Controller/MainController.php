<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchForm;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="app_accueil")
     */
    public function accueil(SortieRepository $sortieRepository, Request $request): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);

        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $sorties = $sortieRepository->findSearch($data);
        return $this->render('main/accueil.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView()
        ]);
       // return $this->render('main/accueil.html.twig');
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
