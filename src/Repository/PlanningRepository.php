<?php

namespace App\Repository;
use App\Entity\Planning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Planning|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planning|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planning[]    findAll()
 * @method Planning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Planning $entity, bool $flush = true): void
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
    public function remove(Planning $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }




    // /**
    //  * @return Planning[] Returns an array of Planning objects
    //  */
    /*
    
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Planning
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    
    public function findPosteLibreByAtelier($ateliers)
    {
        $atelierId = $ateliers->getId();
        $query = $this->getEntityManager()
            ->createQuery(
                "select po from App\Entity\Poste po where po.id NOT IN (
                    select p.id from App\Entity\Planning p where p.ateliers = '$atelierId')
                    "
            );
        return $query->getResult();
    }

    public function findUsagerLibreByAtelier($ateliers)
    {
        $usagerId = $ateliers->getId();
        $query = $this->getEntityManager()
            ->createQuery(
                "select u from App\Entity\Usager u where u.id NOT IN (
                    select p.id from App\Entity\Planning p where p.usagers = '$usagerId')
                    "
            );
        return $query->getResult();
    }

    public function findUsagerByAtelier($ateliers): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.ateliers = :id')
            ->setParameter('id', $ateliers)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findActifAtelier(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p, a')
            ->innerjoin('p.ateliers', 'a')
             ->groupBy('a.libelle, a.date, a.heureDebut')
             ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function countUsagerByAtelier(): array
    {
        return $this->createQueryBuilder('p')

        ->select('count(p)')
        ->innerJoin('p.ateliers', 'a')
        // ->innerjoin('p.usagers', 'u')
        // ->innerjoin('p.postes', 'po')
        // ->where('p.ateliers = :id')
        // ->setParameter('id', $idAtelier)
        ->groupBy('a.libelle, a.date, a.heureDebut')
        ->orderBy('a.id', 'ASC')
        ->getQuery()
        ->getResult()
        ;

    }
    

    public function findLibellePosteByPlanning(): array
    {
        return $this->createQueryBuilder('p')

        ->select('count(po.libelle)')
        ->innerJoin('p.postes', 'po','a')
        // ->innerjoin('p.usagers', 'u')
        // ->innerjoin('p.postes', 'po')
        // ->where('p.ateliers = :id')
        // ->setParameter('id', $idAtelier)
        ->groupBy('p.ateliers, a.date, a.heureDebut')
        ->orderBy('p.id', 'ASC')
        ->getQuery()
        ->getResult()
        ;

    }

    // public function findLibellePosteByPlanning(): array
    // {
    //     return $this->createQueryBuilder('p')

    //     ->select(' po.libelle')
    //     ->innerJoin('p.postes', 'po')
    //     // ->innerjoin('p.usagers', 'u')
    //     // ->innerjoin('p.postes', 'po')
    //     // ->where('p.ateliers = :id')
    //     // ->setParameter('id', $idAtelier)
    //     ->getQuery()
    //     ->getResult()
    //     ;

    
}
