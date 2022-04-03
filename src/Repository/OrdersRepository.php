<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    public function getOrderList($filters, $page = 1, $page_size = 10): Paginator
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->add('select', 'o')
            ->from(Orders::class, 'o')
            ->where('o.deleted = 0');

        if ($filters['filter_date']) {
            $qb->andWhere('o.date_order LIKE :date')
                ->setParameter('date', '%'.$filters['filter_date'].'%');
        }

        if (!empty($filters['filter_status']) && is_numeric($filters['filter_status']) && $filters['filter_status'] < 99) {
            $qb->andWhere('o.status = :status')
                ->setParameter('status', $filters['filter_status']);
        }

        $qb->setFirstResult( $page_size * ( $page - 1 ) )
            ->setMaxResults( $page_size );

        return new Paginator($qb->getQuery());

    }

    // /**
    //  * @return Orders[] Returns an array of Orders objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Orders
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
