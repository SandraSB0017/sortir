<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/creation/{id}", name="sortie_creation", requirements={"id"="\d+"})
     */
    public function creation(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        ParticipantRepository $participantRepository,
        EtatRepository $etatRepository
    ):Response
    {
        $sortie = new Sortie();

        $sortieForm= $this ->createForm(SortieType::class, $sortie);
        $participant = $participantRepository->find($id);
        $sortieForm->handleRequest($request);

        if($sortieForm ->isSubmitted() && $sortieForm->isValid()) {
            if ($request->request->get('publier')){
                $etat= $etatRepository->findOneBy(['libelle'=>'ouverte']);

            }
            else {
                $etat= $etatRepository->findOneBy(['libelle'=>'créée']);

            }
            $sortie->setEtat($etat);
            $sortie->setOrganisateur($participant);
            $sortie->setCampus($participant->getCampus());

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie ajoutée !');

            return $this->redirectToRoute('sortir_app_accueil');
        }

        return $this->render('sortie/creation.html.twig',[
            'sortie'=>$sortie,
            'sortieForm'=>$sortieForm->createView()
        ]);

    }

    /**
     * @Route("/addLieu", name="sortie_add_Lieu")
     */

    public function addLieu(EntityManagerInterface $entityManager,
                            Request $request ){


        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);
        if($lieuForm->isSubmitted() && $lieuForm->isValid() ){

            $entityManager->persist(($lieu));
            $entityManager->flush();
            $this->addFlash('success', 'Nouveau lieu ajouté !');
            return $this->redirectToRoute('sortie_sortie_add_Lieu');

        }
        return $this->render('sortie/LieuCreation.html.twig',[
            'lieu' => $lieu,
            'lieuForm'=>$lieuForm->createView()
        ]);
    }


    /**
     * @Route("sortie/{id}/publier", name="sortie_publier", requirements={"id"="\d+"})
     */
    public function publier( int $id,
                             SortieRepository $sortieRepository,
                             EntityManagerInterface $entityManager,
                             EtatRepository $etatRepository
    ): Response
    {

        $sortie = $sortieRepository->find($id);

        if($sortie){
            //vérifier le context : etat et si l'utilisateur est organisateur
            $etat= $etatRepository->findOneBy(['libelle'=>'ouverte']);
            $sortie->setEtat($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie publiée !');
        }

        return $this->redirectToRoute('sortir_app_accueil');
    }


    /**
     * @Route("/sortie/{id}/annulation", name="sortie_annulation", requirements={"id"="\d+"})
     */
    public function annulation(int $id,
                               SortieRepository $sortieRepository,
                               EntityManagerInterface $entityManager,
                               EtatRepository $etatRepository,
                                Request $request
    ): Response
    {

        $sortie = $sortieRepository->find($id);

        if($sortie){
            $sortieForm = $this->createForm(SortieType::class, $sortie);
            $etat = new Etat();
            $etat= $etatRepository->findOneBy(['libelle'=>'annulée']);
            $sortie->setEtat($etat);
            $sortieForm->handleRequest($request);
            if($sortieForm ->isSubmitted() && $sortieForm->isValid()) {
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'La sortie a été annulée');
                return $this->redirectToRoute('sortir_app_accueil');
            }

        }

        return $this->render('sortie/annulation.html.twig', [
            'sortie'=>$sortie,
            'sortieForm'=>$sortieForm->createView()
        ]);
    }

    /**
     * @Route("/sortie/{id}/modifier", name="sortie_modifier", requirements={"id"="\d+"})
     */
    public function modifier(int $id, Request $request, EntityManagerInterface $entityManager, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id);
                if($sortie){
                    $sortieForm = $this->createForm(SortieType::class, $sortie);
                    $sortieForm->handleRequest($request);

                    if($sortieForm ->isSubmitted() && $sortieForm->isValid()) {
                        $entityManager->persist($sortie);
                        $entityManager->flush();
                        $this->addFlash('success', 'La sortie a été modifiée');
                        return $this->redirectToRoute('sortir_app_accueil');
                    }
                }
            return $this->render('sortie/modifier.html.twig',[
            'sortie' => $sortie,
            'sortieForm' => $sortieForm->createView()
             ]);
          }


    /**
     * @Route("/sortie/{id}/afficher", name="sortie_afficher", requirements={"id" = "\d+"})
     *
     */

    public function afficherSortie(int $id,
                                   EntityManagerInterface $entityManager,
                                   SortieRepository $sortieRepository
    ): Response
    {
        $sortie = $sortieRepository->find($id);
         if(!$sortie){
             $this->createNotFoundException('Sortie non trouvée');
         }

        return $this->render('sortie/afficher.html.twig',[
            "sortie" => $sortie
        ]);
    }
        //création de la méthode pour l'API

            //return $this->json($lieu, Response::HTTP_OK, [], ['groups' => "liste_lieux"]);

    }
