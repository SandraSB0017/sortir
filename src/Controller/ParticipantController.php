<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route ("/participant", name="participant_")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route("", name="app_participant")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @Route("/monProfil/{id}", name="participant_monProfil", requirements={"id"="\d+"})
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

            }
        return $this->render('participant/monProfil.html.twig',[
            'participant'=>$participant,
            'editForm' => $form->createView(),
        ]);
    }


}
