<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant", name="app_participant")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @Route("/monProfil/{id}", name="participant_monProfil")
     */
    public function monProfil(int $id,
                              ParticipantRepository $participantRepository,
                              Request $request,
                              EntityManagerInterface $entityManager
    ): Response
    {
        $participant = $participantRepository->find($id);

        $form = $this->createForm(RegistrationFormType::class, $participant);
        $form->handleRequest($request);





            if( $form->get('saveAndAdd')->isClicked() && $form->isValid())

            {
               $entityManager->persist($participant);
               $entityManager->flush();

               $this->addFlash('succes', 'Profil modifié');

               return $this->redirectToRoute('app_accueil');

            }/*else if($form->get('annuler')->isClicked())

            {
                return $this->redirectToRoute('participant_monProfil',
                    ['id'=>$participant->getId()]

                );
            }*/



        return $this->render('participant/monProfil.html.twig',[
            'participant'=>$participant,
            'editForm' => $form->createView(),
        ]);
    }


}
