<?php

namespace App\Repository;

use App\Entity\Mark;
use App\Entity\Receipt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mark>
 *
 * @method Mark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mark[]    findAll()
 * @method Mark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mark::class);
    }

    public function getAverageMarks($receiptId){
        $conn=$this->getEntityManager()->getConnection();
        $sql= '
            SELECT COUNT(m.value) as count, AVG(m.value) as avg FROM mark m
            WHERE receipt_id = :receiptId;
        ';

        $stmt=$conn->prepare($sql);
        $resultSet=$stmt->executeQuery(['receiptId'=>$receiptId]);

        $marks=$resultSet->fetchAllAssociative();
        return [
            'avg'=> $marks[0]["avg"],
            'count'=> $marks[0]["count"]
        ];
    }

    public function receiptMarks(Receipt $receipt, ?int $max){
        
        $wMarks=$this->createQueryBuilder('m')
        ->where('m.receipt = :receipt')
        ->setParameter("receipt", $receipt)
        ->orderBy('m.id');

        if(is_integer($max) && $max>0){
            $wMarks->setMaxResults($max);
        }
        
        return $wMarks->getQuery()->getResult();

    
    }

    public function add(Mark $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Mark $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return Mark[] Returns an array of Mark objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mark
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
