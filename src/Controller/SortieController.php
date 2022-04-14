<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\ParticipantRepository;
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
        ParticipantRepository $participantRepository


    ):Response
    {
        $sortie = new Sortie();
        //$pseudo=$this->getUser()->getUserIdentifier();
        //$sortie->setOrganisateur($pseudo);
        $sortieForm= $this ->createForm(SortieType::class, $sortie);
        $participant = $participantRepository->find($id);
        $sortieForm->handleRequest($request);

        if($sortieForm ->isSubmitted() && $sortieForm->isValid() ){

            $sortie->setOrganisateur($participant);

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie créée !');
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('sortie/creation.html.twig',[
            'sortie'=>$sortie,
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
     * @Route("/afficher", name="sortie_afficher")
     */
    public function afficher()
    {
        return $this->render('sortie/afficher.html.twig');
    }
}
