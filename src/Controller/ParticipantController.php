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
 * @Route("/participant", name="participant_")
 */
class ParticipantController extends AbstractController
{
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
               $file= $form->get('photo')->getData();
                    if($file){
                        $newFilename = $participant->getPrenom()."-".$participant->getId().".".$file->guessExtension();
                        $file->move($this->getParameter('images_directory'), $newFilename);
                        $participant->setPhoto($newFilename);
                    }
               $entityManager->persist($participant);
               $entityManager->flush();
               $this->addFlash('succes', 'Profil modifié');
               return $this->redirectToRoute('sortir_app_accueil');

            }
        return $this->render('participant/monProfil.html.twig',[
            'participant'=>$participant,
            'editForm' => $form->createView(),
        ]);
    }


}
