<?php

namespace App\Repository;

use App\Entity\Archive;
use App\Entity\Nomenclature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Archive|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archive|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archive[]    findAll()
 * @method Archive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchiveRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, Archive::class);
    }

    public function findByNomenclatureTitle($title) {
        $nb = $this->getEntityManager()->createQueryBuilder();

        $nb->add('select', 'nom')
            ->from(Nomenclature::class, 'nom')
            ->andWhere('nom.full_name = :val')
            ->setParameter('val', $title);

        $nomenclature = $nb->getQuery()->execute();

//        $nr = $this->getEntityManager()->getRepository(Nomenclature::class);
        //$nomenclature = $nr->findBy(['full_name' => $title]);

        if (count($nomenclature) > 0) {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->select(
                'DATE(ar.date_paper) as datePaper',
                'SUM(ar.count) as count')
                ->from(Archive::class, 'ar')
                ->where('ar.nomenclature = :nomen')
                ->setParameter('nomen',  $nomenclature[0])
                ->groupBy('ar.date_paper')
                ->orderBy("ar.date_paper", "ASC");

            $result = $qb->getQuery()->execute() ;
            $lastElem = count($result);
            if ($lastElem > 0) {
                return array_merge(["dates"=>$result], ["price" => $nomenclature[0]->getPrice()]);
            } else {
                return [];
            }

        } else {
            return null;
        }
    }


    public function getArchiveList($filters = null, $page = 1, $page_size = 10)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
//        $sql = <<<SQL
//SET @sql = NULL;
//SELECT
//    GROUP_CONCAT(DISTINCT CONCAT(
//            'SUM(
//            CASE WHEN nm.short_name = "', short_name, '" THEN ar.count ELSE 0 END)
//  AS "', short_name, '"')
//        )
//INTO @sql
//FROM nomenclature;
//
//SET @sql = CONCAT('SELECT ar.date_paper, ', @sql,
//                  ' FROM archive AS ar LEFT JOIN nomenclature AS nm ',
//                  ' ON ar.nomenclature_id = nm.id GROUP BY ar.date_paper');
//SELECT @sql;
//
//PREPARE stmt FROM @sql;
//EXECUTE stmt;
//DEALLOCATE PREPARE stmt;
//SQL;

        $qb->select(
            '(ar.date_paper) as datePaper',
            '(ar.nomenclature) as nomenclature',
            'nm.full_name as title',
            'SUM(ar.count) as count')
            ->from(Archive::class, 'ar')
            ->leftJoin(Nomenclature::class, 'nm', 'WITH','nm.id = ar.nomenclature')
            ->groupBy('ar.nomenclature, nm.short_name, ar.date_paper');
//            ->groupBy('nm.short_name')
//            ->groupBy('ar.date_paper');

//        if ($filters['filter_date']) {
//            $qb->andWhere('o.date_order LIKE :date')
//                ->setParameter('date', '%'.$filters['filter_date'].'%');
//        }
//
//        if (!empty($filters['filter_status']) && is_numeric($filters['filter_status']) && $filters['filter_status'] < 99) {
//            $qb->andWhere('o.status = :status')
//                ->setParameter('status', $filters['filter_status']);
//        }

//        $qb->setFirstResult( $page_size * ( $page - 1 ) )
//            ->setMaxResults( $page_size );
//
        //$pg = new Paginator($qb);
        return $qb->getQuery()->execute() ;//$pg->setUseOutputWalkers(false); //$resultSet->fetchAllAssociative();
    }

    // /**
    //  * @return Archive[] Returns an array of Archive objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Archive
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
