<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SearchForm;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Service\MajEtat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use function PHPUnit\Framework\greaterThan;



/**
 * @Route("/sortir", name="sortir_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="app_accueil")
     */
    public function accueil(SortieRepository $sortieRepository, EtatRepository $etatRepository, Request $request, MajEtat $majEtat, EntityManagerInterface $entityManager): Response
    {
        $majEtat->etatMaj($sortieRepository,$etatRepository, $entityManager);
        $currentParticipant = $this->getUser();
        $data = new SearchData();
        //$data->page = $request->get('page', 1);

        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $sorties = $sortieRepository->findSearch($data,$currentParticipant);
        return $this->render('main/accueil.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView()
        ]);
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
     * @Route("/detail_participant/{id}", name="detail_participant", requirements={"id"="\d+"})
     */
    public function detail(int $id, ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->find($id);
        return $this->render('participant/detail_participant.html.twig',[
            "participant" => $participant
        ]);
    }

    /**
     * @Route("/sortie/{id}/participant", name="sortie_participant", requirements={"id"="\d+"})
     */
    public function ajoutParticipant(
                                     int $id,
                                     EntityManagerInterface $entityManager,
                                     ParticipantRepository $participantRepository,
                                     SortieRepository $sortieRepository
    ): Response
    {
        $sortie = $sortieRepository->find($id);
        $time = date('y/m/d');

        if($sortie){

            if(($sortie->getDateLimiteInscription()->format('y/m/d') > $time)&&($sortie->getNbInscriptionsMax() > $sortie->getParticipants()->count())&&($sortie->getEtat()->getLibelle()=='ouverte')){
                $sortie->addParticipant($this->getUser());
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Inscription réussie !');
            }
            else{
                $this->addFlash('echec', 'Vous ne pouvez plus vous inscrire à cette sortie!');
            }
        }

        return $this->redirectToRoute('sortir_app_accueil');
    }

    /**
     * @Route("/sortie/{id}/participant-desinscription", name="sortie_participant_desinscription", requirements={"id"="\d+"})
     */
    public function removeDuParticipant(
        int $id,
        EntityManagerInterface $entityManager,
        ParticipantRepository $participantRepository,
        SortieRepository $sortieRepository
    ): Response
    {
        $sortie = $sortieRepository->find($id);
        $time = date('y/m/d');

        if($sortie){

            if (($sortie->getDateLimiteInscription()->format('y/m/d') > $time) && ($sortie->getEtat()->getLibelle() == 'ouverte')) {
                $sortie->removeParticipant($this->getUser());
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Inscription annulée !');
            } else {
                $this->addFlash('echec', 'Vous ne pouvez plus vous désinscrire à cette sortie!');
            }
        }

        return $this->redirectToRoute('sortir_app_accueil');
    }


}
