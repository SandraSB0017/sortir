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
       $query = $entityManager->createQuery('SELECT s FROM App\Entity\Sortie s WHERE s.etat.libelle = :etat1 OR s.etat.libelle = :etat2');
       $query->setParameters(array(
           'etat1' => 'passée',
           'etat2' =>'cloturée',
       ));

       $sorties = $query->getResult();
       var_dump($sorties);
    }
}
