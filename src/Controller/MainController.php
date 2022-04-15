<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SearchForm;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="app_accueil")
     */
    public function accueil(SortieRepository $sortieRepository, Request $request): Response
    {
        $currentParticipant = $this->getUser();
        $data = new SearchData();
        $data->page = $request->get('page', 1);

        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $sorties = $sortieRepository->findSearch($data,$currentParticipant);
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

    public function inscriptionSortie (int $id, Participant $participant, Sortie $sortie,EntityManagerInterface $entityManager)
    {

        $sortie->addParticipant($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();



        return $this->redirectToRoute('app_accueil');
    }

    /**
     * @Route("/sortie/{id}/participant", name="sortie_participant")
     */
    public function ajoutParticipant(
                                     int $id,

                                     EntityManagerInterface $entityManager,
                                     ParticipantRepository $participantRepository,
                                     SortieRepository $sortieRepository


    ): Response
    {



        $sortie = $sortieRepository->find($id);
      /*  if($sortie->isSubscribed($participant)) {
                 $participant = $participantRepository->findOneBy([
                 'sortie' => $sortie,
                 'participant' => $participant
             ]);

             $sortie->removeParticipant($participant);
             $entityManager->persist($sortie);
             $entityManager->flush();
             return $this->json([
                 'code' => 200,
                 'message' => 'participant supprimé',
                 'participant' => $participantRepository->count(['sortie' => $sortie])
             ], 200);

         } else{*/

        $sortie->addParticipant($this->getUser());

        $entityManager->persist($sortie);

        $entityManager->flush();

        return $this->redirectToRoute('app_accueil');


        //return $this->json(['code'=>200, 'message'=>'ça marche bien'],200);
    }

    /**
     * @Route("/sortie/{id}/participant-desinscription", name="sortie_participant_desinscription")
     */
    public function removeDuParticipant(
        int $id,

        EntityManagerInterface $entityManager,
        ParticipantRepository $participantRepository,
        SortieRepository $sortieRepository


    ): Response
    {
        $sortie = $sortieRepository->find($id);
        $sortie->removeParticipant($this->getUser());

        $entityManager->persist($sortie);

        $entityManager->flush();

        return $this->redirectToRoute('app_accueil');
    }


}
