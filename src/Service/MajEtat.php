<?php

namespace App\Service;


use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Sortie;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class MajEtat
{
    private SortieRepository $sortieRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(SortieRepository $sortieRepository, EntityManagerInterface $entityManager)
    {
        $this->sortieRepository=$sortieRepository;
        $this->entityManager=$entityManager;
    }

    public function etatMaj(SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {
        $sortiesOuvertes = $sortieRepository->findOuverte();
        if($sortiesOuvertes){
            foreach ($sortiesOuvertes as $sortie)
            {
                $etat = $etatRepository->findOneBy(['libelle'=>'cloturée']);
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }
        $sortiesCloturees = $sortieRepository->findCloturee();
        if($sortiesCloturees){
            foreach ($sortiesCloturees as $sortie)
            {
                $etat = $etatRepository->findOneBy(['libelle'=>'activité en cours']);
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }
        $sortiesEnCours = $sortieRepository->findEnCours();
        if($sortiesEnCours){
            foreach ($sortiesEnCours as $sortie)
            {
                $etat = $etatRepository->findOneBy(['libelle'=>'passée']);
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }
        $sortiesPassees = $sortieRepository->findPassee();
        if($sortiesPassees){
            foreach ($sortiesPassees as $sortie)
            {
                $etat = $etatRepository->findOneBy(['libelle'=>'historisée']);
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }
        $sortiesAnnulees = $sortieRepository->findAnnulee();
        if($sortiesAnnulees){
            foreach ($sortiesAnnulees as $sortie)
            {
                $etat = $etatRepository->findOneBy(['libelle'=>'historisée']);
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }
    }
}
