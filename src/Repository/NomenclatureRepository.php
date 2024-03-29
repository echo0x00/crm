<?php

namespace App\Repository;

use App\Entity\Nomenclature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nomenclature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nomenclature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nomenclature[]    findAll()
 * @method Nomenclature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NomenclatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nomenclature::class);
    }

    // /**
    //  * @return Nomenclature[] Returns an array of Nomenclature objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getById($value): ?Nomenclature
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
