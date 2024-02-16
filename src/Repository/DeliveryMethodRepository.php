<?php

namespace App\Repository;

use App\Entity\DeliveryMethod;
use Doctrine\Persistence\ManagerRegistry;
use App\DataFixtures\Customer\DeliveryMethodFixtures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<DeliveryMethod>
 *
 * @method DeliveryMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryMethod[]    findAll()
 * @method DeliveryMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryMethod::class);
    }

    public function save(DeliveryMethod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DeliveryMethod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Undocumented function
     *
     * @param integer $weight
     * @param array $areas
     * @return DeliveryMethod[]
     */
    public function findByWeightAndAreaArray(int $weight, array $areas): array
    {
        return $this->createQueryBuilder('dm')
                    ->andWhere('dm.minWeight <= :weight')
                    ->andWhere('dm.maxWeight >= :weight')
                    ->setParameter('weight', $weight)
                    ->andWhere('dm.destinationArea IN(:areas)')
                    ->setParameter('areas', $areas)
                    ->getQuery()
                    ->getResult()
                    ;
    }

//    /**
//     * @return DeliveryMethod[] Returns an array of DeliveryMethod objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DeliveryMethod
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
