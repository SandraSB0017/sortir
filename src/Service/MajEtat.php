<?php

namespace App\Service;


use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Sortie;

class MajEtat
{
    private SortieRepository $sortieRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(SortieRepository $sortieRepository, EntityManagerInterface $entityManager)
    {
        $this->sortieRepository=$sortieRepository;
        $this->entityManager=$entityManager;
    }

    public function etatMaj(EntityManagerInterface $entityManager)
    {
       $query = $entityManager->createQuery('SELECT s FROM App\Entity\Sortie s 
                                            INNER JOIN App\Entity\Etat e
                                            WHERE s.etat = e.id AND (e.libelle = :etat1 OR e.libelle = :etat2 
                                            OR e.libelle = :etat3 OR e.libelle = :etat4 OR e.libelle = :etat5)
                                            ');
       $query->setParameters(array(
           'etat1' => 'ouverte',
           'etat2' => 'cloturée',
           'etat3' => 'activité en cours',
           'etat4' => 'passée',
           'etat5' => 'annulée'
       ));
       $sorties = $query->getResult();
       $time = date('y/m/d h:i');

        if (!empty($sortie)) {
            foreach ($sorties as $sortie)
               {

               }
        }


       //var_dump($sorties);
    }
}
