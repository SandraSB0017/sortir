<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     *
     * @Route("/sortie", name="app_sortie")
     */
    public function index(): Response
    {
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/creation/{id}", name="sortie_creation")
     */
    public function creation(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        ParticipantRepository $participantRepository,
        LieuRepository $lieuRepository


    ):Response
    {
        $sortie = new Sortie();
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);


        //$pseudo=$this->getUser()->getUserIdentifier();
        //$sortie->setOrganisateur($pseudo);
        $sortieForm= $this ->createForm(SortieType::class, $sortie);
        $participant = $participantRepository->find($id);
        $sortieForm->handleRequest($request);
        $lieuForm->handleRequest($request);

        if($sortieForm ->isSubmitted() && $sortieForm->isValid() || $lieuForm->isSubmitted()){ //&& $lieuForm->isValid() ){

            $sortie->setOrganisateur($participant);

            $entityManager->persist($sortie);
            $entityManager->persist(($lieu));
            $entityManager->flush();
            $this->addFlash('success', 'Sortie créée !');
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('sortie/creation.html.twig',[
            'sortie'=>$sortie,
            'lieuform'=>$lieuForm->createView(),
            'sortieForm'=>$sortieForm->createView()
        ]);




    }


    /**
     * @Route("/annulation", name="sortie_annulation")
     */
    public function annulation()
    {
        return $this->render('sortie/annulation.html.twig');
    }

    /**
     * @Route("/modifier", name="sortie_modifier")
     */
    public function modifier()
    {
        return $this->render('sortie/modifier.html.twig');
    }


    /**
     * @Route("/sortie/{id}/afficher", name="sortie_afficher")
     */

    public function afficherSortie(int $id,
                                   EntityManagerInterface $entityManager,
                                   SortieRepository $sortieRepository
    ): Response
    {
        $sortie = $sortieRepository->find($id);
        $time = date('d/m/y');

          $entityManager->flush();

        return $this->render('sortie/afficher.html.twig',[
            "sortie" => $sortie
        ]);
    }

    }
