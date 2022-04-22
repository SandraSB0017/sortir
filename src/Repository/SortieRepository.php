<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param SearchData $search
     * @param UserInterface $currentParticipant
     * @return array
     */
    public function findSearch(SearchData $search, UserInterface $currentParticipant): array
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select('c', 's', 'p','e')
            ->join('s.campus', 'c')
            ->join('s.organisateur','p')
            ->join('s.etat', 'e')
            ->andWhere('e.libelle != \'historisée\'');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('s.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->campus)) {
            $query = $query
                ->andWhere('s.campus = :campus')
                ->setParameter('campus', $search->campus);
        }

        if (!empty($search->dateDebut)) {
            $query = $query
                ->andWhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $search->dateDebut);
        }

        if (!empty($search->dateFin)) {
            $query = $query
                ->andWhere('s.dateHeureDebut <= :dateFin')
                ->setParameter('dateFin', $search->dateFin);
        }

        if (!empty($search->organisateur)) {
            $query = $query
                ->andWhere('s.organisateur = :currentParticipant')
                ->setParameter('currentParticipant', $currentParticipant);
        }
        if (!empty($search->inscrit)) {
            $query
                ->andWhere(':user member of s.participants')
                ->setParameter('user', $currentParticipant);
        }
        if (!empty($search->nonInscrit)){
            $query
                ->andWhere(':participant not member of s.participants')
                ->setParameter('participant', $currentParticipant);
        }
        if (!empty($search->sortiePassees)) {
            $query = $query
                ->andWhere('e.libelle = \'passée\'');
        }

        return $query->getQuery()->getResult();

    }

    public function findOuverte(){
        $time =date('Y-m-d h:i:s');
        $query = $this
            ->createQueryBuilder('s')
            ->select('s','e')
            ->join('s.etat', 'e')
            ->andWhere('e.libelle = \'ouverte\'')
            ->andWhere('s.dateLimiteInscription < :time')
            ->setParameter('time',$time);
        return $query->getQuery()->getResult();
    }

    public function findCloturee(){
        $time = date('Y-m-d h:i:s');
        $query = $this
            ->createQueryBuilder('s')
            ->select('s','e')
            ->join('s.etat', 'e')
            ->andWhere('e.libelle = \'cloturée\'')
            ->andWhere('s.dateHeureDebut < :time')
            ->andWhere('DATE_ADD(s.dateHeureDebut , s.duree, \'SECOND\') > :time')
            ->setParameter('time',$time);
        return $query->getQuery()->getResult();
    }

    public function findEnCours(){
        $time =date('Y-m-d h:i:s');
        $query = $this
            ->createQueryBuilder('s')
            ->select('s','e')
            ->join('s.etat', 'e')
            ->andWhere('e.libelle = \'activité en cours\'')
            ->andWhere('DATE_ADD(s.dateHeureDebut , s.duree, \'SECOND\') < :time')
            ->setParameter('time',$time);
        return $query->getQuery()->getResult();
    }

    public function findPassee(){
        $time =date('Y-m-d h:i:s');
        $query = $this
            ->createQueryBuilder('s')
            ->select('s','e')
            ->join('s.etat', 'e')
            ->andWhere('e.libelle = \'passée\'')
            ->andWhere("DATE_ADD(s.dateHeureDebut, 1 , 'MONTH') < :time")
            ->setParameter('time',$time);
        return $query->getQuery()->getResult();
    }

    public function findAnnulee(){
        $time =date('Y-m-d h:i:s');
        $query = $this
            ->createQueryBuilder('s')
            ->select('s','e')
            ->join('s.etat', 'e')
            ->andWhere('e.libelle = \'annulée\'')
            ->andWhere("DATE_ADD(s.dateHeureDebut, 1 , 'MONTH') < :time")
            ->setParameter('time',$time);
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
